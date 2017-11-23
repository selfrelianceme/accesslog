@extends('adminamazing::teamplate')

@section('pageTitle', 'Логи доступа')
@section('content')
	<div class="modal fade bs-example-modal-lg" id="informationTransaction" aria-hidden="true">
	    <div class="modal-dialog modal-lg">
	        <div class="modal-content">
                <div class="modal-header">Информация об операции</div>
                <div class="modal-body">
                    
                </div>
	        </div>
	    </div>
	</div>	

    <div class="row">
        <div class="col-12">
            <div class="card">
                <!-- .left-right-aside-column-->
                <div class="contact-page-aside">
                    <!-- .left-aside-column-->
                    <div class="left-aside">
                        <ul class="list-style-none">
                            <li class="{{(!$Filter_status_code)?'box-label':NULL}}"><a href="{{route('AdminAccessLog')}}">Все ошибки</a></li>
                            <li class="divider"></li>
                            @foreach($CodesStatus as $row)
	                            <li class="{{($Filter_status_code==$row->status_code)?'box-label':NULL}}"><a href="{{route('AdminAccessLog', ['status_code'=>$row->status_code, 'search'=>$Filter_search])}}">{{$row->status_text}} <span>{{$row->count}}</span></a></li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- /.left-aside-column-->
                    <div class="right-aside">
                        
                        <div class="right-page-header">
                            <div class="d-flex">
                                <div class="ml-auto">
                                    <form method="GET" action="{{route('AdminAccessLog', ['status_code'=>$Filter_status_code])}}">
                                    	<input value="{{$Filter_search}}" type="text" name="search" placeholder="Поиск" class="form-control"> 
                                    </form>
                                </div>
                            </div>
                        </div>
						@if(Session::has('success'))
	                        <div class="alert alert-important alert-success alert-rounded">{{Session::get('success')}}</div>     	
		                @endif   
		                @if(Session::has('error'))
	                        <div class="alert alert-important alert-danger alert-rounded">{{Session::get('error')}}</div>     	
		                @endif                	
	                	<form class="FormOperations" method="POST" action="">
	                        {{ csrf_field() }}                        
	                        <div class="table-responsive">
	                            <table id="demo-foo-addrow" class="table m-t-30 table-hover no-wrap contact-list" data-page-size="10">
	                                <thead>
			                            <tr>
			                                <th class="text-center">
		                                    	@if(!$logs->isEmpty())
		                                    		<button type="button" id="SelectAll" class="btn btn-sm btn-success">Select all</button>
		                                    	@endif
		                                    </th>
			                                <th>ID #</th>
			                                <th>Пользователь</th>
			                                <th>Метод</th>
			                                <th>Путь</th>
			                                <th>IP</th>
			                                <th>Дата</th>
			                            </tr>
			                        </thead>
	                                <tbody>
	                                    @if($logs)
			                            	@foreach($logs as $row)
					                            <tr>
					                                <td class="text-center">
				                                		<input value="{{$row->id}}" id="checkbox0" name="application[]" type="checkbox">
				                                	</td>
					                                <td>{{$row->id}}</td>
					                                <td>
					                                	@if($row->user_id)
					                                		<a target="_blank" href="{{route('AdminUsersEdit', $row->user_id)}}">{{$row->user_name}}</a>
					                                	@endif
					                                </td>
					                                <td>
					                                	<span class="label label-{{$row->status_color}}">{{$row->method}}:{{$row->status_code}}</span>
					                                </td>
					                                <td>
					                                	{{$row->request_uri}}
														@if(false && $row->request_parameters)
					                                		<br/>
					                                		{{$row->request_parameters}}
					                                	@endif
					                                	<br/><a href="#informationTransaction" class="show_info_transaction" 
														data-is_json='{{print_r(json_decode($row->is_json, true), true)}}'
														data-request_json='{{print_r(json_decode($row->request_json, true), true)}}'
														data-response_json='{{print_r(json_decode($row->response_json, true), true)}}'
														data-request_parameters='{{print_r(json_decode($row->request_parameters, true), true)}}'
														data-user_agent='{{print_r(json_decode($row->user_agent, true), true)}}'
														data-request_uri='{{print_r(json_decode($row->request_uri, true), true)}}'
				                                    	data-toggle="modal" href="">Данные запроса</a>					                                	
					                                </td>
					                                <td>{{$row->ip}}</td>
					                                <td>{{$row->created_at}}</td>
					                            </tr>
			                            	@endforeach
			                            @endif
	                                </tbody>
	                            </table>
	                        </div>
							<div class="form-actions">
								<div class="row">
									<div class="col-md-4"><button data-action="{{route('AdminAccessLogDestroy')}}" type="button" class="btn btn-block btn-danger MyAction">Удалить логи</button></div>
								</div>
	                        </div>
						</form>
                        <!-- .left-aside-column-->

                        <nav aria-label="Page navigation example" class="m-t-40">
			                {{ $logs->links('vendor.pagination.bootstrap-4') }}
			            </nav>
                    </div>
                    <!-- /.left-right-aside-column-->
                </div>
            </div>
        </div>
    </div>
	@push('scripts')
		<script type="text/javascript">
			$(function(){
				var state = 1;
				$(document).on('click', '#SelectAll', function(){
					var form = $(this).closest('form');
					if(state == 1){
						form.find('input[type=checkbox]').not(":disabled").attr( "checked" , true)
						state = 0;
					}else{
						form.find('input[type=checkbox]').not(":disabled").attr( "checked" , false)
						state = 1;			
					}
				});

				$(document).on('click', '.MyAction', function(){
					var action = $(this).data('action');
					var form = $(this).closest('form');
					form.attr('action', action);
					form.submit();
				});		


				$(document).on('click', '.show_info_transaction', function(){
					var tr = $(this).data('transaction');
					var data = $(this).data();
					$('.modal-body').html('');
					$.each(data, function(i, el){
						var title = i;
						if(i != 'toggle' && el != ''){
							$('.modal-body').append('<div class="form-group"><label>'+title+'</label><textarea style="height: 250px;" class="form-control">'+data[i]+'</textarea></div>');
						}
					});
				});				


			})
		</script>
	@endpush
@endsection