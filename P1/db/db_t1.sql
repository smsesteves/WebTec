PRAGMA foreign_keys = ON;

CREATE TABLE user_roles (
	id INTEGER PRIMARY KEY,
	roles_desc VARCHAR
);

CREATE TABLE users (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	username VARCHAR NOT NULL UNIQUE,
	password VARCHAR NOT NULL,
        nome VARCHAR,
        email VARCHAR,
        morada VARCHAR,
        contacto VARCHAR,
	role_id INTEGER REFERENCES user_roles(id) ON DELETE CASCADE on update cascade
);


CREATE TABLE customers (
	CustomerID INTEGER PRIMARY KEY AUTOINCREMENT,
	AccountID VARCHAR(30) DEFAULT 'Desconhecido',
	CustomerTaxID VARCHAR(20),
	CompanyName VARCHAR(100),
        /*BillingAdress_*/
            AddressDetail VARCHAR(100),
            City VARCHAR(50),
            PostalCode VARCHAR(20),
            Country VARCHAR(2),  
	Email VARCHAR(60),
	SelfBillingIndicator INTEGER DEFAULT 0
);

/* Se o pais tiver mais que 2 letras, fazer crop e contar apenas as primeiras 3*/
CREATE TRIGGER crop_country AFTER INSERT ON customers
WHEN length(new.Country) > 0
BEGIN
   UPDATE customers SET Country =  substr(new.Country, 0, 3);
END;

CREATE TABLE products (
  ProductType VARCHAR(1) DEFAULT 'P',
  ProductCode INTEGER PRIMARY KEY AUTOINCREMENT,
  ProductDescription VARCHAR(200),
  UnitPrice FLOAT, 
  UnitOfMeasure VARCHAR,
  ProductNumberCode VARCHAR(50)
);

CREATE TRIGGER number_code_null AFTER INSERT ON products
WHEN new.ProductNumberCode is null
BEGIN
   UPDATE products SET ProductNumberCode = new.ProductCode WHERE ProductCode = new.ProductCode;
END;

CREATE TRIGGER unit_price_not_negative AFTER INSERT ON products
WHEN new.UnitPrice <= 0
BEGIN
   UPDATE products SET UnitPrice =  1.0 WHERE ProductCode = new.ProductCode;
END;

CREATE TABLE invoices(
    InvoiceNo INTEGER PRIMARY KEY AUTOINCREMENT,
    /*DocumentStatus*/
        InvoiceStatus VARCHAR(1), 
        InvoiceStatusDate DATETIME,
        SourceBilling VARCHAR(1) DEFAULT 'P',
        SourceID VARCHAR(30),
    Hash VARCHAR(172) DEFAULT '0',
    InvoiceDate DATE,
    InvoiceType VARCHAR(2),
    /*SpecialRegimes*/
        SelfBillingIndicator INTEGER DEFAULT '0',
        CashVATSchemeIndicator INTEGER DEFAULT '0',
        ThirdPartiesBillingIndicator INTEGER DEFAULT '1',
    SystemEntryDate DATETIME,
	CustomerID INTEGER,
    /* DocumentTotals */
        TaxPayable FLOAT DEFAULT 0, 
        NetTotal FLOAT DEFAULT 0,
        GrossTotal FLOAT DEFAULT 0,
	
    FOREIGN KEY(CustomerID) REFERENCES customers(CustomerID) ON DELETE CASCADE on update cascade
);

CREATE TABLE taxes(
    TaxType VARCHAR(3),
    TaxCountryRegion VARCHAR(5),
    TaxCode VARCHAR(10),
    TaxPercentage FLOAT,
    Description VARCHAR(255),

    PRIMARY KEY(TaxType)
);

CREATE TABLE lines(
    LineNumber INTEGER PRIMARY KEY AUTOINCREMENT,
    TaxType VARCHAR(3) REFERENCES taxes(TaxType) on delete cascade on update cascade,
    InvoiceNo INTEGER,
    ProductCode INTEGER,
    ProductDescription VARCHAR(200),
    Quantity FLOAT,
    UnitOfMeasure VARCHAR(20), 
    UnitPrice FLOAT,
    CreditAmount FLOAT, /* Total = Quantity*UnitPrice*/
    DebitAmount FLOAT DEFAULT '0.0',
    TaxPointDate DATE DEFAULT (datetime('now', 'localtime')),
    Description VARCHAR(200),

    FOREIGN KEY(InvoiceNo) REFERENCES invoices(InvoiceNo) on delete cascade on update cascade,
    FOREIGN KEY(ProductCode) REFERENCES products(ProductCode) on delete cascade on update cascade
);


CREATE TRIGGER insert_tax_payble_invoices 
AFTER INSERT ON invoices
BEGIN
    UPDATE invoices SET TaxPayable = (SELECT taxPayable FROM (SELECT invoices.InvoiceNo AS InvoiceNo, sum(lines.CreditAmount*taxes.TaxPercentage/100 ) AS taxPayable
    FROM lines, invoices, taxes
    WHERE lines.InvoiceNo = invoices.InvoiceNo
    AND lines.TaxType = taxes.TaxType
    GROUP BY invoices.InvoiceNo )
    WHERE InvoiceNo = new.InvoiceNo ) WHERE new.InvoiceNo = invoices.InvoiceNo;
END;

CREATE TRIGGER insert_net_total_invoices 
AFTER INSERT ON invoices
BEGIN
    UPDATE invoices SET NetTotal = (SELECT netTotal FROM (SELECT invoices.InvoiceNo AS InvoiceNo, sum(lines.CreditAmount) AS netTotal
    FROM lines, invoices
    WHERE lines.InvoiceNo = invoices.InvoiceNo
    GROUP BY invoices.InvoiceNo) 
    WHERE InvoiceNo = new.InvoiceNo) WHERE new.InvoiceNo = invoices.InvoiceNo;
END;

CREATE TRIGGER insert_gross_total_invoices
AFTER INSERT ON invoices
BEGIN
    UPDATE invoices SET GrossTotal = (SELECT grossTotal FROM (SELECT invoices.InvoiceNo AS InvoiceNo, netTotal_+taxPayable_ AS grossTotal 
    FROM invoices,  
            (SELECT invoices.InvoiceNo AS InvoiceNo1, sum(lines.CreditAmount*taxes.TaxPercentage/100) AS taxPayable_
            FROM lines, invoices, taxes
            WHERE lines.InvoiceNo = invoices.InvoiceNo
            AND lines.TaxType = taxes.TaxType
            GROUP BY invoices.InvoiceNo ),
            (SELECT invoices.InvoiceNo AS InvoiceNo2, sum(lines.CreditAmount) AS netTotal_
            FROM lines, invoices
            WHERE lines.InvoiceNo = invoices.InvoiceNo
            GROUP BY invoices.InvoiceNo)
    WHERE InvoiceNo1 = InvoiceNo2
    AND invoices.InvoiceNo = InvoiceNo1
    AND invoices.InvoiceNo = InvoiceNo2
    GROUP BY invoices.InvoiceNo )
    WHERE InvoiceNo = new.InvoiceNo) WHERE new.InvoiceNo = invoices.InvoiceNo;
END;

CREATE TRIGGER update_tax_payble_invoices 
AFTER UPDATE ON invoices
BEGIN
    UPDATE invoices SET TaxPayable = (SELECT taxPayable FROM (SELECT invoices.InvoiceNo AS InvoiceNo, sum(lines.CreditAmount*taxes.TaxPercentage/100 ) AS taxPayable
    FROM lines, invoices, taxes
    WHERE lines.InvoiceNo = invoices.InvoiceNo
    AND lines.TaxType = taxes.TaxType
    GROUP BY invoices.InvoiceNo )
    WHERE InvoiceNo = new.InvoiceNo ) WHERE new.InvoiceNo = invoices.InvoiceNo;
END;

CREATE TRIGGER update_net_total_invoices 
AFTER UPDATE ON invoices
BEGIN
    UPDATE invoices SET NetTotal = (SELECT netTotal FROM (SELECT invoices.InvoiceNo AS InvoiceNo, sum(lines.CreditAmount) AS netTotal
    FROM lines, invoices
    WHERE lines.InvoiceNo = invoices.InvoiceNo
    GROUP BY invoices.InvoiceNo) 
    WHERE InvoiceNo = new.InvoiceNo) WHERE new.InvoiceNo = invoices.InvoiceNo;
END;

CREATE TRIGGER update_gross_total_invoices
AFTER UPDATE ON invoices
BEGIN
    UPDATE invoices SET GrossTotal = (SELECT grossTotal FROM (SELECT invoices.InvoiceNo AS InvoiceNo, netTotal_+taxPayable_ AS grossTotal 
    FROM invoices,  
            (SELECT invoices.InvoiceNo AS InvoiceNo1, sum(lines.CreditAmount*taxes.TaxPercentage/100) AS taxPayable_
            FROM lines, invoices, taxes
            WHERE lines.InvoiceNo = invoices.InvoiceNo
            AND lines.TaxType = taxes.TaxType
            GROUP BY invoices.InvoiceNo ),
            (SELECT invoices.InvoiceNo AS InvoiceNo2, sum(lines.CreditAmount) AS netTotal_
            FROM lines, invoices
            WHERE lines.InvoiceNo = invoices.InvoiceNo
            GROUP BY invoices.InvoiceNo)
    WHERE InvoiceNo1 = InvoiceNo2
    AND invoices.InvoiceNo = InvoiceNo1
    AND invoices.InvoiceNo = InvoiceNo2
    GROUP BY invoices.InvoiceNo )
    WHERE InvoiceNo = new.InvoiceNo) WHERE new.InvoiceNo = invoices.InvoiceNo;
END;

CREATE TRIGGER update_tax_payble_lines
AFTER UPDATE ON lines
BEGIN
UPDATE lines SET UnitPrice = (SELECT UnitPrice FROM products WHERE products.ProductCode = new.ProductCode) WHERE LineNumber = new.LineNumber;
    UPDATE lines SET ProductDescription = (SELECT ProductDescription FROM products WHERE products.ProductCode = new.ProductCode) WHERE LineNumber = new.LineNumber;
    UPDATE lines SET UnitOfMeasure = (SELECT UnitOfMeasure FROM products WHERE products.ProductCode = new.ProductCode) WHERE LineNumber = new.LineNumber;
    UPDATE lines SET CreditAmount = (SELECT UnitPrice FROM products WHERE products.ProductCode = new.ProductCode) * new.Quantity WHERE LineNumber = new.LineNumber;
    UPDATE invoices SET TaxPayable = (SELECT taxPayable FROM (SELECT invoices.InvoiceNo AS InvoiceNo, sum(lines.CreditAmount*taxes.TaxPercentage/100 ) AS taxPayable
    FROM lines, invoices, taxes
    WHERE lines.InvoiceNo = invoices.InvoiceNo
    AND lines.TaxType = taxes.TaxType
    GROUP BY invoices.InvoiceNo )
    WHERE InvoiceNo = new.InvoiceNo ) WHERE new.InvoiceNo = invoices.InvoiceNo;
END;

CREATE TRIGGER update_net_total_lines
AFTER UPDATE ON lines
BEGIN
    UPDATE lines SET UnitPrice = (SELECT UnitPrice FROM products WHERE products.ProductCode = new.ProductCode) WHERE LineNumber = new.LineNumber;
    UPDATE lines SET ProductDescription = (SELECT ProductDescription FROM products WHERE products.ProductCode = new.ProductCode) WHERE LineNumber = new.LineNumber;
    UPDATE lines SET UnitOfMeasure = (SELECT UnitOfMeasure FROM products WHERE products.ProductCode = new.ProductCode) WHERE LineNumber = new.LineNumber;
    UPDATE lines SET CreditAmount = (SELECT UnitPrice FROM products WHERE products.ProductCode = new.ProductCode) * new.Quantity WHERE LineNumber = new.LineNumber;
    
    UPDATE invoices SET NetTotal = (SELECT netTotal FROM (SELECT invoices.InvoiceNo AS InvoiceNo, sum(lines.CreditAmount) AS netTotal
    FROM lines, invoices
    WHERE lines.InvoiceNo = invoices.InvoiceNo
    GROUP BY invoices.InvoiceNo) 
    WHERE InvoiceNo = new.InvoiceNo) WHERE new.InvoiceNo = invoices.InvoiceNo;
END;

CREATE TRIGGER update_gross_total_lines
AFTER UPDATE ON lines
BEGIN
    UPDATE lines SET UnitPrice = (SELECT UnitPrice FROM products WHERE products.ProductCode = new.ProductCode) WHERE LineNumber = new.LineNumber;
    UPDATE lines SET ProductDescription = (SELECT ProductDescription FROM products WHERE products.ProductCode = new.ProductCode) WHERE LineNumber = new.LineNumber;
    UPDATE lines SET UnitOfMeasure = (SELECT UnitOfMeasure FROM products WHERE products.ProductCode = new.ProductCode) WHERE LineNumber = new.LineNumber;
    UPDATE lines SET CreditAmount = (SELECT UnitPrice FROM products WHERE products.ProductCode = new.ProductCode) * new.Quantity WHERE LineNumber = new.LineNumber;
    
    UPDATE invoices SET GrossTotal = (SELECT grossTotal FROM (SELECT invoices.InvoiceNo AS InvoiceNo, netTotal_+taxPayable_ AS grossTotal 
    FROM invoices,  
            (SELECT invoices.InvoiceNo AS InvoiceNo1, sum(lines.CreditAmount*taxes.TaxPercentage/100) AS taxPayable_
            FROM lines, invoices, taxes
            WHERE lines.InvoiceNo = invoices.InvoiceNo
            AND lines.TaxType = taxes.TaxType
            GROUP BY invoices.InvoiceNo ),
            (SELECT invoices.InvoiceNo AS InvoiceNo2, sum(lines.CreditAmount) AS netTotal_
            FROM lines, invoices
            WHERE lines.InvoiceNo = invoices.InvoiceNo
            GROUP BY invoices.InvoiceNo)
    WHERE InvoiceNo1 = InvoiceNo2
    AND invoices.InvoiceNo = InvoiceNo1
    AND invoices.InvoiceNo = InvoiceNo2
    GROUP BY invoices.InvoiceNo )
    WHERE InvoiceNo = new.InvoiceNo) WHERE new.InvoiceNo = invoices.InvoiceNo;
END;

CREATE TRIGGER insert_tax_payble_lines
AFTER INSERT ON lines
BEGIN
    UPDATE lines SET UnitPrice = (SELECT UnitPrice FROM products WHERE products.ProductCode = new.ProductCode) WHERE LineNumber = new.LineNumber;
    UPDATE lines SET ProductDescription = (SELECT ProductDescription FROM products WHERE products.ProductCode = new.ProductCode) WHERE LineNumber = new.LineNumber;
    UPDATE lines SET UnitOfMeasure = (SELECT UnitOfMeasure FROM products WHERE products.ProductCode = new.ProductCode) WHERE LineNumber = new.LineNumber;
    UPDATE lines SET CreditAmount = (SELECT UnitPrice FROM products WHERE products.ProductCode = new.ProductCode) * new.Quantity WHERE LineNumber = new.LineNumber;
   
    UPDATE invoices SET TaxPayable = (SELECT taxPayable FROM (SELECT invoices.InvoiceNo AS InvoiceNo, sum(lines.CreditAmount*taxes.TaxPercentage/100 ) AS taxPayable
    FROM lines, invoices, taxes
    WHERE lines.InvoiceNo = invoices.InvoiceNo
    AND lines.TaxType = taxes.TaxType
    GROUP BY invoices.InvoiceNo )
    WHERE InvoiceNo = new.InvoiceNo ) WHERE new.InvoiceNo = invoices.InvoiceNo;
END;

CREATE TRIGGER insert_net_total_lines
AFTER INSERT ON lines
BEGIN
    UPDATE lines SET UnitPrice = (SELECT UnitPrice FROM products WHERE products.ProductCode = new.ProductCode) WHERE LineNumber = new.LineNumber;
    UPDATE lines SET ProductDescription = (SELECT ProductDescription FROM products WHERE products.ProductCode = new.ProductCode) WHERE LineNumber = new.LineNumber;
    UPDATE lines SET UnitOfMeasure = (SELECT UnitOfMeasure FROM products WHERE products.ProductCode = new.ProductCode) WHERE LineNumber = new.LineNumber;
    UPDATE lines SET CreditAmount = (SELECT UnitPrice FROM products WHERE products.ProductCode = new.ProductCode) * new.Quantity WHERE LineNumber = new.LineNumber;

    UPDATE invoices SET NetTotal = (SELECT netTotal FROM (SELECT invoices.InvoiceNo AS InvoiceNo, sum(lines.CreditAmount) AS netTotal
    FROM lines, invoices
    WHERE lines.InvoiceNo = invoices.InvoiceNo
    GROUP BY invoices.InvoiceNo) 
    WHERE InvoiceNo = new.InvoiceNo) WHERE new.InvoiceNo = invoices.InvoiceNo;
END;

CREATE TRIGGER insert_gross_total_lines
AFTER INSERT ON lines
BEGIN
    UPDATE lines SET UnitPrice = (SELECT UnitPrice FROM products WHERE products.ProductCode = new.ProductCode) WHERE LineNumber = new.LineNumber;
    UPDATE lines SET ProductDescription = (SELECT ProductDescription FROM products WHERE products.ProductCode = new.ProductCode) WHERE LineNumber = new.LineNumber;
    UPDATE lines SET UnitOfMeasure = (SELECT UnitOfMeasure FROM products WHERE products.ProductCode = new.ProductCode) WHERE LineNumber = new.LineNumber;
    UPDATE lines SET CreditAmount = (SELECT UnitPrice FROM products WHERE products.ProductCode = new.ProductCode) * new.Quantity WHERE LineNumber = new.LineNumber;

    UPDATE invoices SET GrossTotal = (SELECT grossTotal FROM (SELECT invoices.InvoiceNo AS InvoiceNo, netTotal_+taxPayable_ AS grossTotal 
    FROM invoices,  
            (SELECT invoices.InvoiceNo AS InvoiceNo1, sum(lines.CreditAmount*taxes.TaxPercentage/100) AS taxPayable_
            FROM lines, invoices, taxes
            WHERE lines.InvoiceNo = invoices.InvoiceNo
            AND lines.TaxType = taxes.TaxType
            GROUP BY invoices.InvoiceNo ),
            (SELECT invoices.InvoiceNo AS InvoiceNo2, sum(lines.CreditAmount) AS netTotal_
            FROM lines, invoices
            WHERE lines.InvoiceNo = invoices.InvoiceNo
            GROUP BY invoices.InvoiceNo)
    WHERE InvoiceNo1 = InvoiceNo2
    AND invoices.InvoiceNo = InvoiceNo1
    AND invoices.InvoiceNo = InvoiceNo2
    GROUP BY invoices.InvoiceNo )
    WHERE InvoiceNo = new.InvoiceNo) WHERE new.InvoiceNo = invoices.InvoiceNo;
END;



