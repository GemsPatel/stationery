<style type="text/css">
.list td {
	padding:6px !important;
}
</style>

<?php 
if(isset($detail['error']))
{
	echo $detail['error'];
}
else
{?>
    <table class="list">
    <thead>
        <tr>
            <td class="left">Field</td>
            <td class="left">Value</td>
        </tr>
    </thead>
    <tbody>
    <?php
        foreach($detail as $k=>$d){?>
        <tr>
            <td class="left"><?php echo ucwords(str_replace('_',' ',$k));?></td>
            <td class="left"><?php echo ($d) ? $d : '-';?></td>
        </tr>
        <?php }?>
    </tbody>
    </table>
<?php }?>


