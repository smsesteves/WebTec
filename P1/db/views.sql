create view associar_linhas_e_taxas as
select lines.LineNumber as LineNumber, sum(taxes.TaxPercentage)/100 as taxa
from lines, taxes, taxes_lines 
where lines.LineNumber = taxes_lines.LineNumber
and taxes.TaxType = taxes_lines.TaxType group by 
lines.LineNumber;

create view calcular_taxa_linha as 
select lines.LineNumber as LineNumber, lines.CreditAmount*associar_linhas_e_taxas.taxa as taxa_aplicada
from associar_linhas_e_taxas, lines
where lines.LineNumber = associar_linhas_e_taxas.LineNumber
group by lines.LineNumber;

create view calcular_TaxPayable as
select invoices.InvoiceNo as InvoiceNo, sum(calcular_taxa_linha.taxa_aplicada) as taxPayable
from calcular_taxa_linha, lines, invoices
where lines.InvoiceNo = invoices.InvoiceNo
and lines.LineNumber = calcular_taxa_linha.LineNumber
group by invoices.InvoiceNo;

create view calcular_NetTotal as
select invoices.InvoiceNo as InvoiceNo, sum(lines.CreditAmount) as netTotal
from lines, invoices
where lines.InvoiceNo = invoices.InvoiceNo
group by invoices.InvoiceNo;

create view calcular_GrossTotal as
select invoices.InvoiceNo as InvoiceNo, (calcular_NetTotal.netTotal+calcular_TaxPayable.taxPayable) as grossTotal 
from invoices, calcular_TaxPayable, calcular_NetTotal 
where calcular_NetTotal.InvoiceNo = calcular_TaxPayable.InvoiceNo
and invoices.InvoiceNo = calcular_NetTotal.InvoiceNo
and invoices.InvoiceNo = calcular_TaxPayable.InvoiceNo
group by invoices.InvoiceNo;