<div class="container">
    <div class="row">
        <div class="col col-lg-8 offset-lg-2">
            <h1 class="display-6"><?php echo lang("Kea.hosts_msg_listhost"); ?></h1>
        </div>
    </div>
    <form>
        <div class="row">
            <div class="form-group form-inline col col-lg-8 offset-lg-2">
                <label for="filter_host_pool" class="mr-2"><?php echo lang("Kea.hosts_pools"); ?>:</label>
                <select class="form-control mr-4" name="filter_host_pool" id="filter_host_pool">
                    <?php foreach ($pools as $pools_item) : ?>
                        <option value="<?php echo $pools_item->id; ?>"><?php echo $pools_item->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col col-lg-8 offset-lg-2">
                <label for="filter_host_ip" class="mr-2"><?php echo lang("Kea.hosts_search"); ?>:</label>
                <div class="input-group">
                    <input name="filter_host_ip" type="text" class="form-control" id="filter_host_ip" placeholder="<?php echo lang("Kea.hosts_msg_search"); ?>">
                    <button class="btn btn-outline-secondary" id="filter_clear">
                        Search
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col col-lg-8 offset-lg-2">
                <button type="button" onclick="location.href=" /hosts/add/"" class="btn btn-outline-secondary my-1 ml-auto"><?php echo lang("Kea.hosts_msg_addhost"); ?></button>
            </div>
        </div>
    </form>
    <div class="row">
        <div class="col col-lg-8 offset-lg-2">
            <div class="table-responsive">
                <div id="host_rows"></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var hosts_data = <?php echo $json; ?>;

    function host_in_pool(obj, pool_id) {
        return obj.pool_id == pool_id;
    }

    function host_match(obj, text) {
        if (text.length == 0)
            return true;

        var mac = obj.host_mac.toUpperCase();
        var desc = obj.hostname.toUpperCase();
        var t = text.toUpperCase();
        var t1 = t.replace(":", "");
        var ret =
            mac.indexOf(t1) !== -1 ||
            desc.indexOf(t) !== -1 ||
            obj.host_ip.indexOf(t) !== -1;
        return ret;
    }

    function pretty_mac(mac) {
        var ret = "";
        var len = mac.length / 2;
        for (var i = 0; i < len; i++) {
            var sub = mac.substr(i * 2, 2);
            if (i != 0)
                ret = ret + ":";
            ret = ret + sub;
        }
        return ret;
    }


    function refresh_host_rows() {
        var filter_pool_id = $("select#filter_host_pool").val();
        var filter_search = $("input#filter_host_ip").val();

        var s_html = "<div id='host_rows'><table class='table table-striped table-sm'>" +
            "<tr><th scope='col'><?php echo lang('Kea.hosts_descr'); ?></th>" +
            "<th scope='col'><?php echo lang('Kea.hosts_ipaddr'); ?></th>" +
            "<th scope='col'><?php echo lang('Kea.hosts_macaddr'); ?></th>" +
            "<th scope='col'><?php echo lang('Kea.hosts_actions'); ?></th></tr>";

        for (i = 0; i < hosts_data.length; i++) {
            var obj = hosts_data[i];
            if (host_in_pool(obj, filter_pool_id) && host_match(obj, filter_search)) {
                s_html = s_html.concat("<tr><td>", obj.hostname, "</td>");
                s_html = s_html.concat("<td>", obj.host_ip, "</td>");
                s_html = s_html.concat("<td>", pretty_mac(obj.host_mac), "</td>");
                s_html = s_html.concat("<td><a href='/hosts/edit/", obj.host_id, "'>", "<?php echo lang('Kea.hosts_edit'); ?>", "</a>&nbsp;");
                s_html = s_html.concat("<a href='/hosts/delete/", obj.host_id, "'>", "<?php echo lang('Kea.hosts_delete'); ?>", "</a></td></tr>");
            }
        }
        s_html = s_html.concat("</table></div>")
        $("div#host_rows").replaceWith(s_html);
    }

    $("input#filter_host_ip").on("input", function(e) {
        refresh_host_rows();
    });

    $("select#filter_host_pool").on("change", function() {
        refresh_host_rows();
    });

    $("button#filter_clear").on("click", function(e) {
        $("input#filter_host_ip").val("");
    });

    $(document).ready(function() {
        refresh_host_rows();
    });
</script>