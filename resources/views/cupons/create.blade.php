@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow rounded-4 border-0 mt-4">
                <div class="card-body p-4 bg-light">
                    <h4 class="text-center fw-bold mb-4">🆕 Novo Cupom</h4>

                    <form method="POST" action="{{ route('cupons.store') }}">
                        @csrf
                        @include('cupons.form')
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
