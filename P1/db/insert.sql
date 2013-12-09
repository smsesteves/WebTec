INSERT INTO user_roles VALUES (1, 'Editor');
INSERT INTO user_roles VALUES (2, 'Reader');
INSERT INTO user_roles VALUES(0,'Admin');
INSERT INTO users(username,password,role_id) VALUES ('goncalobo', '21232f297a57a5a743894a0e4a801fc3',0);


INSERT INTO customers(   CustomerTaxID, CompanyName, AddressDetail, City, PostalCode, Country, Email) 
VALUES ( 123456789, 'Tiago e Irmaos Lda.', 'Rua D. João V n 22', 'Porto, Porto', '4050-300', 'PT', 'tiagosn92@gmail.com');
INSERT INTO customers(   CustomerTaxID, CompanyName, AddressDetail, City, PostalCode, Country, Email) 
VALUES ( 257554123, 'Minipreço', 'Av. Aliados n 123', 'Porto, Porto', '4250-310', 'PT', 'geral@minipreco.pt');
INSERT INTO customers(   CustomerTaxID, CompanyName, AddressDetail, City, PostalCode, Country, Email) 
VALUES ( 253675425, 'Jose Alberto', 'Av. Franca n 53', 'Porto, Porto', '4050-250', 'PT', 'jose_alberto@gmail.pt');
INSERT INTO customers(   CustomerTaxID, CompanyName, AddressDetail, City, PostalCode, Country, Email) 
VALUES ( 178394352, 'Maria de Fátima','Rua Rio de Janeiro, n 100', 'Monte Santo de Minas, Minas Gerais', '37958000', 'BR', 'nazare@netsite.com.br');
INSERT INTO customers(   CustomerTaxID, CompanyName, AddressDetail, City, PostalCode, Country, Email) 
VALUES (253633425, 'Pão de Acucar', 'Av. São Carlos n 12', 'São Carlos, São Paulo', '44444', 'BR', 'mail@paodeacucar.br');
INSERT INTO customers(   CustomerTaxID, CompanyName, AddressDetail, City, PostalCode, Country, Email) 
VALUES ( 111222333, 'Continente', 'Rua da Boavista n 15', 'Porto, Porto', '3250-210', 'PT', 'geral@continente.pt');
INSERT INTO customers(   CustomerTaxID, CompanyName, AddressDetail, City, PostalCode, Country, Email) 
VALUES ( 111222333, 'Pingo Doce', 'Av. Liberdade n 15', 'Lisboa, Lisboa', '3000-210', 'PT', 'geral@pingodoce.pt');





INSERT INTO products (  ProductDescription, UnitPrice, UnitOfMeasure) VALUES ( 'Pao', -1.5, 'g');
INSERT INTO products (  ProductDescription, UnitPrice, UnitOfMeasure) VALUES ( 'Gelado', 0, 'kg');
INSERT INTO products (  ProductDescription, UnitPrice, UnitOfMeasure) VALUES ( 'Batata', 10.0, 'kg');
INSERT INTO products (  ProductDescription, UnitPrice, UnitOfMeasure) VALUES ( 'Sabonete', 1, 'g');
INSERT INTO products (  ProductDescription, UnitPrice, UnitOfMeasure) VALUES ( 'Sopa', 1, 'g');
INSERT INTO products (  ProductDescription, UnitPrice, UnitOfMeasure) VALUES ( 'Pizza', 2.78, 'g');
INSERT INTO products (  ProductDescription, UnitPrice, UnitOfMeasure) VALUES ('Sabão', 2, 'g');
INSERT INTO products (  ProductDescription, UnitPrice, UnitOfMeasure) VALUES ( 'Água', 1.5, 'l');
INSERT INTO products (  ProductDescription, UnitPrice, UnitOfMeasure) VALUES ('Fanta', 1.09, 'l');
INSERT INTO products (  ProductDescription, UnitPrice, UnitOfMeasure) VALUES ('Coca-Cola', 2, 'l');
INSERT INTO products (  ProductDescription, UnitPrice, UnitOfMeasure) VALUES ( 'Pepsi', 1.5, 'l');
INSERT INTO products (  ProductDescription, UnitPrice, UnitOfMeasure) VALUES ( 'Vinho', 5, 'l');
INSERT INTO products (  ProductDescription, UnitPrice, UnitOfMeasure) VALUES ('Queijo', 2.05, 'g');
INSERT INTO products (  ProductDescription, UnitPrice, UnitOfMeasure) VALUES ('Arroz', 2, 'kg');
INSERT INTO products (  ProductDescription, UnitPrice, UnitOfMeasure) VALUES ('Fanta Uva', 1.5, 'l');
INSERT INTO products (  ProductDescription, UnitPrice, UnitOfMeasure) VALUES ('Fanta Ananás', 1.09, 'l');
INSERT INTO products (  ProductDescription, UnitPrice, UnitOfMeasure) VALUES ('Chocolate', 0.5, 'g');
INSERT INTO products (  ProductDescription, UnitPrice, UnitOfMeasure) VALUES ('Cêgripe', 4, 'mg');
INSERT INTO products (  ProductDescription, UnitPrice, UnitOfMeasure) VALUES ('Manteiga', 1, 'g');
INSERT INTO products (  ProductDescription, UnitPrice, UnitOfMeasure) VALUES ('Doce de Leite', 2.05, 'g');
INSERT INTO products (  ProductDescription, UnitPrice, UnitOfMeasure) VALUES ('Feijão', 2, 'g');
INSERT INTO products (  ProductDescription, UnitPrice, UnitOfMeasure) VALUES ('Leite', 1.5, 'l');
INSERT INTO products (  ProductDescription, UnitPrice, UnitOfMeasure) VALUES ('Danone', 1.09, 'l');
INSERT INTO products (  ProductDescription, UnitPrice, UnitOfMeasure) VALUES ('Banana', 2.5, 'kg');
INSERT INTO products (  ProductDescription, UnitPrice, UnitOfMeasure) VALUES ('Laranja', 2, 'kg');
INSERT INTO products (  ProductDescription, UnitPrice, UnitOfMeasure) VALUES ('Tomate', 5, 'kg');
INSERT INTO products (  ProductDescription, UnitPrice, UnitOfMeasure) VALUES ('Alface', 2.05, 'kg');
INSERT INTO products (  ProductDescription, UnitPrice, UnitOfMeasure) VALUES ('Mel', 2, 'l');



INSERT INTO taxes VALUES ('IVA','PT', 'NOR', 23.00, 'IVA Normal');
INSERT INTO taxes VALUES ('IRS','PT','1', 11.5, 'IRS Escala');



INSERT INTO invoices(  InvoiceStatus, InvoiceStatusDate, SourceID, InvoiceDate, InvoiceType, SystemEntryDate, CustomerID)
VALUES ( 'N', '2013-11-11 10:00:00','1', '2013-10-11 10:00:00', 'FT',  '2013-10-11 09:50:00','1');
INSERT INTO invoices(  InvoiceStatus, InvoiceStatusDate, SourceID, InvoiceDate, InvoiceType, SystemEntryDate, CustomerID)
VALUES ( 'N', '2013-11-11 10:00:00','1', '2013-10-11 10:00:00', 'FT',  '2013-10-11 09:50:00','1');
INSERT INTO invoices(  InvoiceStatus, InvoiceStatusDate, SourceID, InvoiceDate, InvoiceType, SystemEntryDate, CustomerID)
VALUES ( 'N', '2013-11-11 10:00:00','1', '2013-10-11 10:00:00', 'FT',  '2013-10-11 09:50:00','1');



INSERT INTO invoices(  InvoiceStatus, InvoiceStatusDate, SourceID, InvoiceDate, InvoiceType, SystemEntryDate, CustomerID)
VALUES ( 'N', '2013-11-11 10:00:00','2', '2013-10-11 10:00:00', 'FT',  '2013-10-11 09:50:00','2');
INSERT INTO invoices(  InvoiceStatus, InvoiceStatusDate, SourceID, InvoiceDate, InvoiceType, SystemEntryDate, CustomerID)
VALUES ( 'N', '2013-11-11 10:00:00','2', '2013-10-11 10:00:00', 'FT',  '2013-10-11 09:50:00','2');
INSERT INTO invoices(  InvoiceStatus, InvoiceStatusDate, SourceID, InvoiceDate, InvoiceType, SystemEntryDate, CustomerID)
VALUES ( 'N', '2013-11-11 10:00:00','3', '2013-10-11 10:00:00', 'FT',  '2013-10-11 09:50:00','3');
INSERT INTO invoices(  InvoiceStatus, InvoiceStatusDate, SourceID, InvoiceDate, InvoiceType, SystemEntryDate, CustomerID)
VALUES ( 'N', '2013-11-11 10:00:00','4', '2013-10-11 10:00:00', 'FT',  '2013-10-11 09:50:00','4');
INSERT INTO invoices(  InvoiceStatus, InvoiceStatusDate, SourceID, InvoiceDate, InvoiceType, SystemEntryDate, CustomerID)
VALUES ( 'N', '2013-11-11 10:00:00','5', '2013-10-11 10:00:00', 'FT',  '2013-10-11 09:50:00','5');
INSERT INTO invoices(  InvoiceStatus, InvoiceStatusDate, SourceID, InvoiceDate, InvoiceType, SystemEntryDate, CustomerID)
VALUES ( 'N', '2013-11-11 10:00:00','6', '2013-10-11 10:00:00', 'FT',  '2013-10-11 09:50:00','6');
INSERT INTO invoices(  InvoiceStatus, InvoiceStatusDate, SourceID, InvoiceDate, InvoiceType, SystemEntryDate, CustomerID)
VALUES ( 'N', '2013-11-11 10:00:00','7', '2013-10-11 10:00:00', 'FT', '2013-10-11 09:50:00','7');



INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (1,'IVA',5, 2);
INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (1,'IRS',2, 1);
INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (1,'IVA',3, 2);
INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (3,'IVA',7, 10);
INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (3,'IVA',8, 20);
INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (4,'IRS',20, 1);
INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (5,'IRS',21, 2);
INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (6,'IVA',22, 3);
INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (7,'IRS',23, 4);
INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (8,'IVA',24, 5);
INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (9,'IVA',25, 6);
INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (9,'IVA',26, 7);
INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (10,'IVA', 1, 6);
INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (10,'IVA', 2, 7);
INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (10,'IRS', 3, 5);
INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (10,'IVA', 4, 7);
INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (10,'IVA', 5, 2);
INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (10,'IVA', 6, 3);
INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (10,'IRS', 7, 3);
INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (10,'IVA', 8, 3);
INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (10,'IVA', 9, 3);
INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (10,'IVA', 6, 3);
INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (1,'IVA',5, 2);
INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (1,'IRS',2, 1);
INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (1,'IVA',3, 2);
INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (2,'IVA',3, 2);
INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (1,'IVA',5, 2);
INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (1,'IRS',2, 1);
INSERT INTO lines(InvoiceNo, TaxType, ProductCode, Quantity) VALUES (1,'IVA',3, 2);
