<?php echo form_open("/hosts/add", "class=\"form\""); ?>
<div class="container">
    <div class="row">
        <div class="col col-lg-8 offset-lg-2">
            <h1 class="display-6"><?php echo lang("Kea.hosts_msg_addhost"); ?></h1>
        </div>
    </div>
    <div class="row">
        <div class="col col-lg-8 offset-lg-2">
            <div class="form-group">
                <label for="host_pool"><?php echo lang("Kea.hosts_pools"); ?></label>
                <select class="form-control" name="host_pool">
                    <?php foreach ($pools as $pools_item) : ?>
                        <option value="<?php echo $pools_item->id; ?>" <?php echo set_select("host_pool", $pools_item->id, $pools_item->id == $host_pool_selected_id ? true : false); ?>>
                            <?php echo $pools_item->name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <!--
  <div class="row">
    <div class="col col-lg-8 offset-lg-2">
      <div class="form-group">
        <label for="host_ip"><?php echo lang("Kea.hosts_ipaddr"); ?></label>
        <input class="form-control" name="host_ip" type="text" value="<?php echo set_value("host_ip"); ?>" readonly/>
        <span class="text-danger font-weight-bold">
          <?php echo $validation->getError("host_ip"); ?>
        </span>
      </div>
    </div>
  </div>
-->
    <div class="row">
        <div class="col col-lg-8 offset-lg-2">
            <div class="form-group">
                <label for="host_mac"><?php echo lang("Kea.hosts_macaddr"); ?></label>
                <input class="form-control" name="host_mac" type="text" placeholder="<?php echo lang("Kea.hosts_msg_hostmac"); ?>" value="<?php echo set_value("host_mac"); ?>" />
                <span class="text-danger font-weight-bold">
                    <?php echo $validation->getError("host_mac"); ?>
                </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col col-lg-8 offset-lg-2">
            <div class="form-group ">
                <label for="hostname"><?php echo lang("Kea.hosts_descr"); ?></label>
                <input class="form-control" name="hostname" type="text" placeholder="<?php echo lang("Kea.hosts_msg_hostname"); ?>" value="<?php echo set_value("hostname"); ?>" />
                <span class="text-danger font-weight-bold">
                    <?php echo $validation->getError("hostname"); ?>
                </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col col-lg-8 offset-lg-2">
            <div class="form-group">
                <button type="submit" class="btn btn-outline-primary my-1" name="submit"><?php echo lang("Kea.hosts_msg_addhost"); ?></button>
                <button type="button" class="btn btn-outline-primary my-1" onclick="location.href=" /hosts""><?php echo lang("Kea.hosts_cancel"); ?></button>
            </div>
        </div>
    </div>
</div>
</form>