@if(@$route_id)
<div class="t">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Solar</label>
                <input type="text" class="form-control" name="name[]" placeholder="Masukkan Nama"
                value="Solar" required readonly>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Amount</label>
                <input type="text" class="form-control" name="amount[]" placeholder="Masukkan Amount"
                required>
            </div>
        </div>
    </div>
</div>
<div class="t">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>E-Toll</label>
                <input type="text" class="form-control" name="name[]" placeholder="Masukkan Nama"
                value="E-Toll" required readonly>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Amount</label>
                <input type="text" class="form-control" name="amount[]" placeholder="Masukkan Amount"
                required>
            </div>
        </div>
    </div>
</div>
<div class="t">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Snack dan Air Mineral</label>
                <input type="text" class="form-control" name="name[]" placeholder="Masukkan Nama"
                value="Snack dan Air Mineral" required readonly>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Amount</label>
                <input type="text" class="form-control" name="amount[]" placeholder="Masukkan Amount"
                required>
            </div>
        </div>
    </div>
</div>
<div class="t">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Travel</label>
                <input type="text" class="form-control" name="name[]" placeholder="Masukkan Nama"
                value="Travel" required readonly>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Amount</label>
                <input type="text" class="form-control" name="amount[]" placeholder="Masukkan Amount"
                required>
            </div>
        </div>
    </div>
</div>
<div class="t">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Nota Pembelian</label>
                <input type="text" class="form-control" name="name[]" placeholder="Masukkan Nama"
                value="Nota Pembelian" required readonly>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Amount</label>
                <input type="text" class="form-control" name="amount[]" placeholder="Masukkan Amount"
                required>
            </div>
        </div>
    </div>
</div>
<div id="dynamicAddRemove">
<div class="t">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Maintenance Armada Bus</label>
                <input type="text" class="form-control" name="name[]" placeholder="Masukkan Nama"
                value="Maintenance Armada Bus" required readonly>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Amount</label>
                <input type="text" class="form-control" name="amount[]" placeholder="Masukkan Amount"
                required>
            </div>
        </div>
    </div>
</div>
</div>
@else
<div id="dynamicAddRemove">
    <div class="t">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Pengeluaran</label>
                    <input type="text" class="form-control" name="name[]" placeholder="Masukkan Nama"
                    value="{{isset($name) ? $name : ''}}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Amount</label>
                    <input type="text" class="form-control" name="amount[]" placeholder="Masukkan Amount"
                    required>
                </div>
            </div>
        </div>
    </div>
</div>
@endif