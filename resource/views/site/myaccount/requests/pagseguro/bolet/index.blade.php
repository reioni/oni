@extends('templates.site')

@section('title', 'Pagamento por boleto para o pedido #' . $requestmodel->id)
@section('url', route('site.myaccount.requests.show.bolet', ['id' => $requestmodel->id]))
@section('keywords', config('app.keywords'))
@section('description', config('app.description'))
@section('image', public_path('assets/img/site/core-img/favicon.ico'))
@section('image_width', 200)
@section('image_height', 200)

@if(isset($session_id) && !empty($session_id))
    @section('container')
    <section class="container">
        <div class="content">
            @include('includes.site.account.menu')
            <div class="cont-content">
                <h1 style="margin-top: 20px;">Pagamento por Boleto</h1><hr />
                <p>Ao clicar no botão abaixo você irá gerar o seu boleto de pagamento</p>
    
                <div class="alert alert-danger" id="message-request" style="display: none;"></div>
    
                <form action="{{ route('site.myaccount.requests.show.bolet.store', ['id' => $requestmodel->id]) }}" method="POST" id="form-payment">
                    <input type="hidden" name="session_id" id="session_id" value="{{ $session_id }}">
                    <input type="hidden" name="sender_hash" id="sender_hash">
                    <button type="submit" title="Gerar Boleto" class="btn btn-success btn-payment" target="_blank" data-linkdisable="true" style="margin: 30px 0px; padding: 15px; width: 100%;"><strong>Gerar boleto R$ {{ number_format($requestmodel->payment->amountFormat, 2, ',', '.') }}</strong></button>
                </form>
    
                <h2 style="margin-top: 40px;">Pedido #{{ $requestmodel->id }}</h2><hr />
                @include('includes.site.requests.body')
            </div>
        </div>
    </section>
    @endsection

    @section('scripts')
        @if(config('store.payment.production'))
        <script type="text/javascript" src="https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
        @else
        <script type="text/javascript" src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
        @endif

        <script type="text/javascript" src="{{ public_path('assets/js/site/pagseguro.js') }}"></script>

        <script type="text/javascript">
            $(document).ready(function(){
                setSessionId($('#session_id').val())

                $('#form-payment').submit(function(){
                    event.preventDefault()

                    let form = this

                    // Gera o hash do sender e gera o boleto
                    getSenderHash(function(response){
                        if(response.status == 'error') {
                            $('#message-request').text('OCORREU UM ERRO AO TENTAR GERAR O SEU BOLETO, FAVOR TENTAR NOVAMENTE!').show()
                            return false;
                        }

                        $('#sender_hash').val(response.senderHash);

                        $.ajax({
                            url: form.action,
                            method: form.method,
                            data: $(form).serialize(),
                            dataType: 'json',
                            processData: false,
                            beforeSend: function(){
                                showLoad('Gerando boleto, aguarde...')
                            },
                            success: function(response){
                                if(response.result){
                                    $('.cont-content').html('<h2>Boleto Gerado com Sucesso!</h2>')
                                    $('.cont-content').append('<p>Para imprimir o seu boleto basta clicar no botão abaixo:</p>')
                                    $('.cont-content').append(`<a href="${response.data.paymentLink}" target="_blank" title="Imprimir Boleto" class="btn btn-success" style="margin-top: 20px;">Imprimir Boleto <i class="fa fa-print"></i></a>`)
                                }else{
                                    $('#message-request').text('OCORREU UM ERRO AO TENTAR GERAR O SEU BOLETO, FAVOR TENTAR NOVAMENTE!').show()
                                }
                            },
                            error: function(response){
                                $('#message-request').text('OCORREU UM ERRO AO TENTAR GERAR O SEU BOLETO, FAVOR TENTAR NOVAMENTE!').show()
                            },
                            complete: function(){
                                hideLoad()
                            }
                        })
                    })
                })
            })
        </script>
    @endsection

@else
    @section('container')
    <section class="container">
        <div class="content">
            @include('includes.site.account.menu')
            <div class="cont-content">
                @include('includes.site.message', [
                    'title' => 'ERRO AO CARREGAR A PÁGINA',
                    'message' => 'Ocorreu um erro ao tentar carregar esta página por favor dé um refresh para recarregá-la novamente!',
                    'success' => false
                ])
            </div>
        </div>
    </section>
    @endsection
@endif

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ public_path('assets/css/site/myaccount.css') }}">
@endsection