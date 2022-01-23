<div class="form-group content-group mb-3 border p-2 bg-light {{ $class ?? null }}">
	<input type="hidden" name="elements[]" value="TEXTEDITOR">
	<div class="mb-5">
		@if(!isset($notoptions))
		<button type="button" class="btn btn-sm btn-danger btn-remove-element float-end" title="Remover Elemento"><i class="fas fa-trash-alt"></i></button>
		@endif
	</div>
	<div class="nosortable">
		<textarea name="{{ $name }}" placeholder="{{ $title }}" rows="{{ $rows ?? 5 }}" required class="textarea-editor form-control required nosortable">{{ old($name) ?? ($value ?? null) }}</textarea>
	</div>
</div>