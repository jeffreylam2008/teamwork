
<h1><?=$title?></h1>


<div class="container">
    <div class="row">
        <div class="col-sm-4">
            <div class="card">
                <div class="card-header text-center">
                <h3><a href="<?=$invoices_url?>">Monthly Invoices</a></h3>
                </div>
                <div class="card-body">
                    <p class="card-text "><div class="text-center"><h1><?=$elem['m_invoices']?></h1></div></p>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="card">
                <div class="card-header text-center">
                <h3><a href="<?=$customer_url?>">Active Customer</a></h3>
                </div>
                <div class="card-body">
                    <p class="card-text"><div class="text-center"><h1><?=$elem['m_customers']?></h1></div></p>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="card">
                <div class="card-header text-center">
                    <h3>This Month Income</h3>
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
                <h3><a href="#">Monthly Purchase</a></h3>
                </div>
                <div class="card-body">
                    <p class="card-text "><div class="text-center"><h1><?=$elem['m_invoices']?></h1></div></p>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="card">
                <div class="card-header text-center">
                <h3><a href="#">Active Items</a></h3>
                </div>
                <div class="card-body">
                    <p class="card-text"><div class="text-center"><h1><?=$elem['m_customers']?></h1></div></p>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="card">
                <div class="card-header text-center">
                    <h3>This Month Expand</h3>
                </div>
                <div class="card-body">
                    <p class="card-text"><div class="text-center"><h1>$111</h1></div></p>
                </div>
            </div>
        </div>
    </div>

</div>