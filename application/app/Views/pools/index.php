<div class="container">
    <div class="row">
        <div class="col col-lg-8 offset-lg-2">
            <h1 class="display-6"><?php echo lang("Kea.pools_msg_title"); ?></h1>
        </div>
    </div>
    <div class="row">
        <div class="col col-lg-8 offset-lg-2">
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <tr>
                        <th scope="col"><?php echo lang("Kea.pools_id"); ?></th>
                        <th scope="col"><?php echo lang("Kea.pools_name"); ?></th>
                        <th scope="col"><?php echo lang("Kea.pools_netaddr"); ?></th>
                    </tr>
                    <?php foreach ($pools as $item) : ?>
                        <tr>
                            <td scope="col"><?php echo $item->id; ?></td>
                            <td scope="col"><?php echo $item->name; ?></td>
                            <td scope="col"><?php echo $item->cidr; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>