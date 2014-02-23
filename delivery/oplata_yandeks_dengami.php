<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "оплата, кошелек, электронные платежи, электронные деньги");
$APPLICATION->SetPageProperty("description", "Оплатить заказ с помощью кошелька Яндекс.Деньги");
$APPLICATION->SetTitle("Оплата Яндекс.Деньгами");
?> 
<p style="font-size: 14px;"><b>У Вас уже открыт счет в Яндекс.Деньгах. Оплатите заказ здесь: </b> </p>
 
<p style="font-size: 14px;"><b> 
    <br />
   </b></p>
 
<p style="font-size: 14px;"><i><font color="#ee1d24">Обратите внимание! </font>Сумма оплаты должна включать комиссию Яндекс за перевод - 0,5% </i></p>
 
<p style="font-size: 14px;"><i>(чтобы узнать финальную сумму платежа, умножьте стоимость заказа на 1,005).</i></p>
 <iframe frameborder="0" allowtransparency="true" scrolling="no" src="https://money.yandex.ru/embed/shop.xml?uid=410011834546520&amp;writer=seller&amp;targets=%D0%BE%D0%BF%D0%BB%D0%B0%D1%82%D0%B0+%D0%BA%D0%BE%D0%BD%D1%82%D0%B0%D0%BA%D1%82%D0%BD%D1%8B%D1%85+%D0%BB%D0%B8%D0%BD%D0%B7+%2F+%D1%81%D1%80%D0%B5%D0%B4%D1%81%D1%82%D0%B2+%D1%83%D1%85%D0%BE%D0%B4%D0%B0&amp;default-sum=&amp;button-text=01&amp;hint=&amp;fio=on&amp;mail=on&amp;phone=on" width="450" height="163"></iframe> 
<br />
 
<br />
 <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>