
<h1><?=$title?></h1>


<div class="container">
    <div class="row">
        <div class="col-sm-4">
            <div class="card">
                <div class="card-header text-center">
                <h3><a href="<?=$invoices_url?>"><?=$this->lang->line("label_monthly_invoices")?></a></h3>
                </div>
                <div class="card-body">
                    <p class="card-text "><div class="text-center"><h1><?=$elem['m_invoices']?></h1></div></p>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="card">
                <div class="card-header text-center">
                <h3><a href="<?=$customer_url?>"><?=$this->lang->line("label_active_customer")?></a></h3>
                </div>
                <div class="card-body">
                    <p class="card-text"><div class="text-center"><h1><?=$elem['m_customers']?></h1></div></p>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="card">
                <div class="card-header text-center">
                    <h3><?=$this->lang->line("label_monthly_income")?></h3>
                </div>
                <div class="card-body">
                    <p class="card-text"><div class="text-center"><h1>$<?=number_format($elem['m_income'],2)?></h1></div></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row row-cols-2">
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="card">
                <div class="card-header text-center">
                <h3><a href="<?=$income_url?>"><?=$this->lang->line("label_monthly_purchase")?></a></h3>
                </div>
                <div class="card-body">
                    <p class="card-text "><div class="text-center"><h1><?=$elem['m_purchases']?></h1></div></p>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="card">
                <div class="card-header text-center">
                <h3><a href="<?=$items_url?>"><?=$this->lang->line("label_active_items")?></a></h3>
                </div>
                <div class="card-body">
                    <p class="card-text"><div class="text-center"><h1><?=$elem['m_items']?></h1></div></p>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="card">
                <div class="card-header text-center">
                    <h3><?=$this->lang->line("label_monthly_expand")?></h3>
                </div>
                <div class="card-body">
                    <p class="card-text"><div class="text-center"><h1>$XXX</h1></div></p>
                </div>
            </div>
        </div>
    </div>

</div>