<ul class="list273 noBrd">
    <?
    $x = 0;
    for ($i = 0; $i <= count($GLOBALS['FOOT_BAN']) - 1; $i++):
        if (!$GLOBALS['FOOT_BAN'][$i]['showed']) {
            $GLOBALS['FOOT_BAN'][$i]['showed'] = true;
            $bann = $GLOBALS['FOOT_BAN'][$i];
            ?>
            <li>

                <table>
                    <td>
                        <? if ($bann['PROPERTY_HREF_VALUE'] == '/question/') { ?>
                            <script>document.write('<a href="<?= $bann['PROPERTY_HREF_VALUE'] ?>" ><img src="<?= CFile::GetPath($bann['PROPERTY_PICT_VALUE']); ?>" alt="" /></a>');</script></td>
                        <? } else { ?>
                            <script>document.write('<noindex><a href="<?= $bann['PROPERTY_HREF_VALUE'] ?>" rel="nofollow"><img src="<?= CFile::GetPath($bann['PROPERTY_PICT_VALUE']); ?>" alt="" /></a></noindex>');</script></td>
                        <? } ?>
                    </tr>
                </table>
                <? /*
                  <table>
                  <tr>
                  <td><a href="<?=$bann['PROPERTY_HREF_VALUE']?>"><img src="<?=CFile::GetPath($bann['PROPERTY_PICT_VALUE']);?>" alt="" /></a></td>
                  <td><p><a href="<?=$bann['PROPERTY_HREF_VALUE']?>"><?=html_entity_decode($bann['NAME']);?></a></p></td>
                  </tr>
                  </table>
                 */ ?>
            </li>
            <?
            $x++;
            if ($x == 4)
                break;
        };
        ?>
    <? endfor; ?>
</ul>
<?
// echo '<pre>';
// print_R($GLOBALS['FOOT_BAN']);
// echo '</pre>';
?>
<div style="clear: both;"></div>
<div id="float_basket_target"></div>
