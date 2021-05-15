<div class="input-group mb-2 input-group-sm">
    <!-- customers Modal -->
    <div class="modal fade" id="customers_modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id=""><?=$this->lang->line("customer_list")?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body-600">
                <!-- content -->
                    <div class="container-fluid">
                        <?=$function_bar?>
                        <table class="table table-sm table-striped" id="cust-list">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col"><?=$this->lang->line("customer_label")?></th>
                                    <th scope="col"><?=$this->lang->line("customer_name")?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if(!empty($ajax['customers'])):
                                        foreach($ajax['customers'] as $k => $v):
                                ?>
                                    <tr data-custcode="<?=$v['cust_code']?>" data-custname="<?=$v['name']?>" data-pmcode="<?=$v['pm_code']?>">
                                        <td><?=$k+1?></td>
                                        <td><?=$v['cust_code']?></td>
                                        <td><?=$v['name']?></td>
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
                    <button type="button" class="btn btn-primary" id="cust-ok" data-dismiss="modal"><?=$this->lang->line("function_ok")?></button>
                </div>
            </div>
        </div>
    </div>
    <!-- customers Modal End -->
</div>