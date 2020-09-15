<!-- items Modal -->
<div class="modal fade" id="items_modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Items List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body-600">
            <!-- content -->
                <div class="container-fluid">
                    <table class="table table-sm table-striped" id="items-list">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Code</th>
                                <th scope="col">Chinese Name</th>
                                <th scope="col">English Name</th>
                                <th scope="col">Unit</th>
                                <th scope="col">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(!empty($ajax["items"])):
                                    foreach($ajax["items"] as $k => $v):
                            ?>
                                    <tr data-itemcode="<?=$v['item_code']?>">
                                        <td><?=$k+1?></td>
                                        <td><?=$v["item_code"]?></td>
                                        <td><?=$v["chi_name"]?></td>
                                        <td><?=$v["eng_name"]?></td>
                                        <td><?=$v["unit"]?></td>
                                        <td>$<?=$v["price"]?></td>
                                    </tr>
                            <?php
                                    endforeach;
                                endif;

                            ?>
                        </tbody>
                    </table>
                </div>
            <!-- content end -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="item-ok" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
<!-- items Modal End -->