@extends('reports.main')

@section('head')
	@parent
	{{ HTML::style('_css/dropzone.css') }}
	{{ HTML::script('_js/dropzone.js') }}
@stop

@section('main')
	<div id="home">
		<div id="drop-holder">
			<form action="/" id="drop" class="dropzone"><input type="hidden" id="shouldsave" name="save" value="0" /></form>
			<a href="#" id="save">Save</a>
			<div id="saving">(This may take a while)</div>
			<div id="saved">Data has been saved</div>
		</div>
		<table>
			<thead><tr></tr></thead>
			<tbody></tbody>
		</table>
	</div>
	<script>
	$(function() {
		Dropzone.autoDiscover = false;
		var myDropzone = new Dropzone("#drop", {
			url: "update-organisations/upload",
			maxFiles: 1,
			dictDefaultMessage: 'Click or drop files here to upload'
		});
		var fileObj;
		myDropzone.on("addedfile", function(file) {
			fileObj = file;
			if (file.type.match(/*spreadsheet*/)) {
				myDropzone.emit("thumbnail", file, "{{URL::to('/')}}/_img/excel.png");
			}
		});
		myDropzone.on("complete", function(file) {
			console.log(file.xhr.response);
			$('#drop').slideUp();
			$('#save').slideDown();
			var json = $.parseJSON(file.xhr.response);
			if(json.table) {
				var keys = Array();
				$.each(json.data, function(key, value) {
					var row = '<tr>';
					if(key == 0) {
						keys = Array();
					}
					$.each(value, function(key2, value2) {
						if(key2 != 0) {
							if(key == 0) {
								keys.push(key2);
							}
							row += '<td>' + value2 + '</td>';
						}
					});
					row += '</tr>';
					$('table tbody').append(row);
				});
				console.log(keys);
				$.each(keys, function(key, value) {
					$('table thead tr').append('<th>' + value + '</th>');
				});
			} else {
				$('#saving').slideUp();
				$('#save').removeClass('loading').slideUp();
				$('#saved').slideDown();
			}
		});
		$('#save').on('click', function() {
			$('#save').addClass('loading').slideDown();
			$('#shouldsave').val(1);
			myDropzone.uploadFile(fileObj);
		});
	});
	</script>
@stop
