<!-- items Modal -->
<div class="modal fade" id="items_modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id=""><?=$this->lang->line('item_list')?></h5>
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
                                <th scope="col"><?=$this->lang->line('item_code')?></th>
                                <th scope="col"><?=$this->lang->line('item_chi_name')?></th>
                                <th scope="col"><?=$this->lang->line('item_eng_name')?></th>
                                <th scope="col"><?=$this->lang->line('item_unit')?></th>
                                <th scope="col"><?=$this->lang->line('item_price')?></th>
                                <th scope="col"><?=$this->lang->line('item_Stockonhand')?></th>
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
                                        <td><?=$v["stockonhand"]?></td>
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?=$this->lang->line("function_close")?></button>
                <button type="button" class="btn btn-primary" id="item-ok" data-dismiss="modal"><?=$this->lang->line("function_ok")?></button>
            </div>
        </div>
    </div>
</div>
<!-- items Modal End -->