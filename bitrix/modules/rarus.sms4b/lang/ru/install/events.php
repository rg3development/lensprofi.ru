<?
$MESS["SMS4B_SALE_NEW_ORDER_NAME"] = "Noviy zakaz";
$MESS["SMS4B_SALE_NEW_ORDER_DESC"] = "#ORDER_ID# - kod zakaza
#ORDER_DATE# - data zakaza
#ORDER_USER# - zakazchik
#PRICE# - summa zakaza
#PHONE_TO# - telephone zakazchika
#ORDER_LIST# - sostav zakaza
#SALE_PHONE# - telephon otdela prodag";
$MESS["SMS4B_SALE_NEW_ORDER_SUBJECT"] = "Noviy zakaz N#ORDER_ID#";
$MESS["SMS4B_SALE_NEW_ORDER_MESSAGE"] = "Vash zakaz N#ORDER_ID# priniat
Stoimost: #PRICE#";

$MESS["SMS4B_SALE_ORDER_CANCEL_NAME"] = "Otmena zakaza";
$MESS["SMS4B_SALE_ORDER_CANCEL_DESC"] = "#ORDER_ID# - kod zakaza
#ORDER_DATE# - data zakaza
#PHONE_TO# - telephone zakazchika
#ORDER_CANCEL_DESCRIPTION# - prichina otmeni
#SALE_PHONE# - telephon otdela prodag";
$MESS["SMS4B_SALE_ORDER_CANCEL_SUBJECT"] = "Otmena zakaza N#ORDER_ID#";
$MESS["SMS4B_SALE_ORDER_CANCEL_MESSAGE"] = "Zakaz N#ORDER_ID# otmenen
#ORDER_CANCEL_DESCRIPTION#";

$MESS["SMS4B_SALE_ORDER_PAID_NAME"] = "Zakaz oplachen";
$MESS["SMS4B_SALE_ORDER_PAID_DESC"] = "#ORDER_ID# - kod zakaza
#ORDER_DATE# - data zakaza
#PHONE_TO# - telephone zakazchika
#SALE_PHONE# - telephon otdela prodag";
$MESS["SMS4B_SALE_ORDER_PAID_SUBJECT"] = "Zakaz N#ORDER_ID# oplachen";
$MESS["SMS4B_SALE_ORDER_PAID_MESSAGE"] = "Zakaz N#ORDER_ID# oplachen";

$MESS["SMS4B_SALE_ORDER_DELIVERY_NAME"] = "Dostavka zakaza razrechena";
$MESS["SMS4B_SALE_ORDER_DELIVERY_DESC"] = "#ORDER_ID# - kod zakaza
#ORDER_DATE# - data zakaza
#PHONE_TO# - telephone zakazchika
#SALE_PHONE# - telephon otdela prodag";
$MESS["SMS4B_SALE_ORDER_DELIVERY_SUBJECT"] = "Dostavka zakaza N#ORDER_ID# razreshena";
$MESS["SMS4B_SALE_ORDER_DELIVERY_MESSAGE"] = "Dostavka zakaza N#ORDER_ID#  razreshena";

$MESS["SMS4B_SALE_RECURRING_CANCEL_NAME"] = "Podpiska otmenena";
$MESS["SMS4B_SALE_RECURRING_CANCEL_DESC"] = "#ORDER_ID# - kod zakaza
#ORDER_DATE# - data zakaza
#PHONE_TO# - telephone zakazchika
#CANCELED_REASON# - prichina otmani
#SALE_PHONE# - telephon otdela prodag";
$MESS["SMS4B_SALE_RECURRING_CANCEL_SUBJECT"] = "Podpiska otmenena";
$MESS["SMS4B_SALE_RECURRING_CANCEL_MESSAGE"] = "Podpiska otmenena
#CANCELED_REASON#";

$MESS["SMS4B_SUBSCRIBE_CONFIRM_NAME"] = "Podtverjdenie podpiski";
$MESS["SMS4B_SUBSCRIBE_CONFIRM_DESC"]= "#ID# - identifikator podpiski
#PHONE_TO# - telefon podpiski
#CONFIRM_CODE# - kod podtvergdeniya
#SUBSCR_SECTION# - razdel s stranicey redaktirovaniya podpiski
#USER_NAME# - imia podpischika
#DATE_SUBSCR# - date dobavleniya/izmenenia adresa
";
$MESS ['SMS4B_SUBSCRIBE_CONFIRM_SUBJECT'] = "Podtverjdenie podpiski";
$MESS ['SMS4B_SUBSCRIBE_CONFIRM_MESSAGE'] = "Info o podpiske:
Telefon #PHONE#
Data dobavlenia/izmeneniya #DATE_SUBSCR#
Kod podtvergdenia: #CONFIRM_CODE#";

$MESS["SMS4B_ORDER_ID"]="kod zakaza"; 
$MESS["SMS4B_ORDER_DATE"]="data zakaza";
$MESS["SMS4B_ORDER_STATUS"]="status zakaza";
$MESS["SMS4B_ORDER_PHONE"]="telefon polzovatelia";
$MESS["SMS4B_STATUS_DESCR"]="opisanie statusa zakaza";
$MESS["SMS4B_STATUS_TEXT"]="tekst";
$MESS["SMS4B_CHANGING_STATUS_TO"]="Izmenenie statusa zakaza na";
$MESS["SMS4B_STATUS_PHONE_SUBJ"]="Izmenenie statusa zakaza N#ORDER_ID#";
$MESS["SMS4B_STATUS_PHONE_BODY1"]="Noviy status zakaza N#ORDER_ID#: ";
$MESS["SMS4B_SALE_PHONE"] = "Telefon otdela prodag";

//techsupport
$MESS["SMS4B_TICKET_NEW_FOR_TECHSUPPORT_NAME"] = "Noviy Ticket";
$MESS["SMS4B_TICKET_NEW_FOR_TECHSUPPORT_DESC"]= "#ID# - nomer obrasheniya
#PHONE_TO# - telefon poddergki
#CRITICAL# - critichnost
#DATE_TICKET# - date dobavleniya
";
$MESS ['SMS4B_TICKET_NEW_FOR_TECHSUPPORT_SUBJECT'] = "Noviy Ticket";
$MESS ['SMS4B_TICKET_NEW_FOR_TECHSUPPORT_MESSAGE'] = "Info o Tickete:
Nomer #ID#
Data dobavlenia #DATE_TICKET#
Critichnost: #CRITICAL#";

$MESS["SMS4B_TICKET_CHANGE_FOR_TECHSUPPORT_NAME"] = "Izmenen Ticket";
$MESS["SMS4B_TICKET_CHANGE_FOR_TECHSUPPORT_DESC"]= "#ID# - nomer obrasheniya
#PHONE_TO# - telefon poddergki
#CRITICAL# - critichnost
#DATE_TICKET# - date dobavlenia
";
$MESS ['SMS4B_TICKET_CHANGE_FOR_TECHSUPPORT_SUBJECT'] = "Izmenen Ticket";
$MESS ['SMS4B_TICKET_CHANGE_FOR_TECHSUPPORT_MESSAGE'] = "Info o Tickete:
Nomer #ID#
Data dobavlenia #DATE_TICKET#
Critichnost: #CRITICAL#
Izmeneniya: #WHAT_CHANGE#
";

?>