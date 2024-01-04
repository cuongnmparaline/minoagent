@extends('layouts.main')

@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="row g-12">
            <div class="col-sm-12 col-xl-12">
                <div class="bg-light rounded h-100 p-4">
                    <h6 class="mb-4">Add New Not Pay</h6>
                    <form action="{{route('management.notpay.store')}}" method="post">
                        @csrf
                        @if ( \Illuminate\Support\Facades\Auth::guard('admin')->user()->role == '1')
                            <div class="col-12 col-xl-12">
                                <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="admin_id">
                                    <option value="">Choose Tech</option>
                                    @foreach($techs as $tech)
                                        <option {{getDataCreateForm('account_data', 'admin_id') == $tech->id ? "selected" : ""}} value="{{$tech->id}}">{{$tech->name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('admin_id'))
                                    <p style="color: #ff0000">{{ $errors->first('admin_id') }}</p>
                                @endif
                            </div>
                        @else
                            <input type="hidden" name="admin_id" value="{{ \Illuminate\Support\Facades\Auth::guard('admin')->id() }}">
                        @endif
                        <div class="col-12 col-xl-12">
                            <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="customer_id" id="customer_select">
                                <option value="">Choose Customer</option>
                                @foreach($customers as $customer)
                                    <option {{getDataCreateForm('account_data', 'customer_id') == $customer->id ? "selected" : ""}} value="{{$customer->id}}">{{$customer->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('customer_id'))
                                <p style="color: #ff0000">{{ $errors->first('customer_id') }}</p>
                            @endif
                        </div>
                        <div class="col-12 col-xl-12">
                            <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="account_id" id="account_select">
                                <option value="">Choose Account</option>
                            </select>
                            @if ($errors->has('account_id'))
                                <p style="color: #ff0000">{{ $errors->first('account_id') }}</p>
                            @endif
                        </div>
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" name="amount">
                            <label for="floatingInput">Amount</label>
                            @if ($errors->has('amount'))
                                <p style="color: #ff0000">{{ $errors->first('amount') }}</p>
                            @endif
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="floatingPassword"
                                   placeholder="Link Image" name="link_image">
                            <label for="floatingPassword">Link Image</label>
                            @if ($errors->has('link_image'))
                                <p style="color: #ff0000">{{ $errors->first('link_image') }}</p>
                            @endif
                        </div>
                        <button class="btn btn-primary w-100 m-2" type="submit">Save</button>
                        <button class="btn btn-secondary w-100 m-2" type="button">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $("#customer_select").on('change',function(){
            $("#account_select").find('option').not(':first').remove();
            $value = $(this).val();
            $.ajax({
                type: 'get',
                url: '{{ route('management.notpay.listAccount') }}',
                data: {
                    'customer_id': $value
                },
                success:function(data){
                    // $('tbody').html(data);
                    $("#account_select").append(data);
                },

            });
        })
        $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
    </script>
@endsection

