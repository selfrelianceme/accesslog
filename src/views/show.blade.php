@extends('adminamazing::teamplate')

@section('pageTitle', 'Логи доступа')
@section('content')
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
                                <div class="align-self-center">
                                	<div class="">
                                        <div class="btn-group m-b-10 m-r-10" role="group" aria-label="Button group with nested dropdown">
                                            <button type="button" class="btn btn-secondary font-18 text-dark"><i class="mdi mdi-delete"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-auto">
                                    <form method="GET" action="{{route('AdminAccessLog', ['status_code'=>$Filter_status_code])}}">
                                    	<input value="{{$Filter_search}}" type="text" name="search" placeholder="Поиск" class="form-control"> 
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <form>
	                            <table id="demo-foo-addrow" class="table m-t-30 table-hover no-wrap contact-list" data-page-size="10">
	                                <thead>
			                            <tr>
			                                <th>
			                                	<div>
	                                                <input type="checkbox" id="checkboxAll">
	                                                <label for="checkboxAll"></label>
	                                            </div>
			                                </th>
			                                <th>ID #</th>
			                                <th>Пользователь</th>
			                                <th>Метод</th>
			                                <th>Путь</th>
			                                <th>IP</th>
			                                <th>Дата</th>
			                                <th>Действие</th>
			                            </tr>
			                        </thead>
	                                <tbody>
	                                    @if($logs)
			                            	@foreach($logs as $row)
					                            <tr>
					                                <td>
	                                                    <div>
	                                                        <input type="checkbox" id="checkbox{{$row->id}}" value="{{$row->id}}">
	                                                        <label for="checkbox{{$row->id}}"></label>
	                                                    </div>
	                                                </td>
					                                <td>{{$row->id}}</td>
					                                <td>
					                                	@if($row->user_id)
					                                		<a href="{{route('AdminUsersEdit', $row->user_id)}}">{{$row->user_name}}</a>
					                                	@endif
					                                </td>
					                                <td>
					                                	<span class="label label-{{$row->status_color}}">{{$row->method}}:{{$row->status_code}}</span>
					                                </td>
					                                <td>
					                                	{{$row->request_uri}}
														@if($row->request_parameters)
					                                		<br/>
					                                		{{$row->request_parameters}}
					                                	@endif
					                                </td>
					                                <td>{{$row->ip}}</td>
					                                <td>{{$row->created_at}}</td>
					                                <td>
					                                    <a href="{{route('AdminAccessLogDestroy', $row->id)}}"><i class="ti-close" aria-hidden="true"></i></a>
					                                </td>
					                            </tr>
			                            	@endforeach
			                            @endif
	                                </tbody>
	                            </table>
                            </form>
                        </div>
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

	<script type="text/javascript">
		$(document).ready(function() {
			$('#checkboxAll').change(function() {
			    var checkboxes = $(this).closest('form').find(':checkbox');
			    checkboxes.prop('checked', $(this).is(':checked'));
			});
		})
	</script>
@endsection