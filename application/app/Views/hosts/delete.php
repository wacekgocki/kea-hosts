<div class="container">
    <div class="row">
        <div class="col col-lg-8 offset-lg-2">
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading"><?php echo lang("Kea.hosts_msg_deletehost"); ?></h4>
                <p><?php echo lang("Kea.hosts_msg_delete"); ?></p>
                <hr>
                <span class="font-weight-bold"><?php echo lang("Kea.hosts_descr"); ?>:&nbsp;</span>
                <?php echo $host_item->hostname; ?><br>
                <span class="font-weight-bold"><?php echo lang("Kea.hosts_ipaddr"); ?>:&nbsp;</span>
                <?php echo $host_item->host_ip; ?><br>
                <span class="font-weight-bold"><?php echo lang("Kea.hosts_macaddr"); ?>:&nbsp;</span>
                <?php echo mac_add_separator($host_item->host_mac); ?>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col col-lg-8 offset-lg-2">
            <?php echo form_open("/hosts/delete/$host_item->host_id", array("class" => "form"), array("host_id" => $host_item->host_id)); ?>
            <input class="btn btn-outline-primary" name="submit" type="submit" value="<?php echo lang("Kea.hosts_delete"); ?>" />
            <input class="btn btn-outline-primary" name="reset" type="reset" onclick="location.href=" /hosts"" value="<?php echo lang("Kea.hosts_cancel"); ?>" />
            </form>
        </div>
    </div>
</div>