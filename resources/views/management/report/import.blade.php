@extends('layouts.main')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="row g-12">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Import Excel</h6>
                    <form action="{{route('management.report.saveImport')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" id="inputGroupFile02" name="reportImport">
                            <label class="input-group-text" for="inputGroupFile02">Upload</label>
                        </div>
                        <button class="btn btn-primary w-100 m-2" type="submit">Save</button>
                        <button class="btn btn-secondary w-100 m-2" type="button">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // const convert = document.getElementById("convert");
        const result = document.getElementById("result");
        const from = document.getElementById("from");
        const amount = document.getElementById("amount");

        from.addEventListener("change", function() {
            let fromCurrency = from.value;
            let toCurrency = 'USD';
            let amt = amount.value;
            fetch(`https://api.exchangerate-api.com/v4/latest/${fromCurrency}`)
                .then(response => {
                    return response.json();
                })
                .then(data => {
                    let rate = data.rates[toCurrency];
                    let total = rate * amt;
                    result.value = total;
                });
        });

        amount.addEventListener("change", function() {
            let fromCurrency = from.value;
            let toCurrency = 'USD';
            let amt = amount.value;
            fetch(`https://api.exchangerate-api.com/v4/latest/${fromCurrency}`)
                .then(response => {
                    return response.json();
                })
                .then(data => {
                    let rate = data.rates[toCurrency];
                    let total = rate * amt;
                    result.value = total;
                });
        });
    </script>
@endsection

