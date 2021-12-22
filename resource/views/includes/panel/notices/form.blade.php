<form action="{{ $action }}" method="{{ ($method != 'GET' && $method != 'POST') ? 'POST' : $method }}" class="border p-4 form-validate" enctype="multipart/form-data">
	@include('includes.messages')
	
	<div class="tabs">
		<ul>
			<li><a href="#tab-article">Artigo</a></li>
			<li><a href="#tab-content">Conteúdo</a></li>
			<li><a href="#tab-categories">Categorias</a></li>
		</ul>

		<div class="article" id="tab-article">
			<input type="hidden" name="_method" value="{{ $method }}">
			<div class="row">
				@include('includes.components.form.file', [
					'type' => 'file', 
					'name' => 'poster', 
					'title' => 'Capa', 
					'accept' => 'image/*',
					'class' => (!isset($notice) ? 'required' : null)
				])

				@include('includes.components.form.input', [
					'type' => 'text', 
					'name' => 'title', 
					'title' => 'Titulo', 
					'class' => 'required',
					'value' => (isset($notice) ? $notice->title : null),
					'required' => true
				])

				@include('includes.components.form.input', [
					'type' => 'text', 
					'name' => 'tags', 
					'title' => 'Tags', 
					'value' => (isset($notice) ? $notice->tags : null)
				])

				<div class="col-md-6">
					@include('includes.components.form.select', [
						'name' => 'visible', 
						'title' => 'Visibilidade',
						'value' => (isset($notice) ? $notice->visible : 1),
						'options' => [
							1 => 'Visível',
							0 => 'Invisivel'
						],
						'required' => true
					])
				</div>
				<div class="col-md-6">
					@include('includes.components.form.select', [
						'name' => 'comments_active', 
						'title' => 'Comentários',
						'value' => (isset($notice) ? $notice->comments_active : 1),
						'options' => [
							1 => 'Ativado',
							0 => 'Desativado'
						],
						'required' => true
					])
				</div>

				@include('includes.components.form.textarea', [
					'name' => 'description',
					'title' => 'Descrição',
					'class' => 'required',
					'value' => (isset($notice) ? $notice->description : null),
					'required' => true
				])
			</div>
		</div>

		<div class="content" id="tab-content">
			<div class="content-elements sortable">
				@php
					$images = [];
				@endphp
				
				@if(isset($notice))
					@foreach(json_decode($notice->content)->elements as $element)
						@if($element->type == 'title')
							@include('includes.components.form.titleeditor', [
								'value' => $element->content,
								'tag' => $element->tag
							])
						@elseif($element->type == 'paragraph')
							@include('includes.components.form.texteditor', [
								'name' => 'paragraphs[]',
								'class' => 'paragraph draggable required',
								'title' => 'Digite o texto do parágrafo...',
								'value' => $element->content
							])
						@elseif($element->type == 'youtube')
							@include('includes.components.form.youtubeeditor', [
								'url' => 'https://www.youtube.com/embed/' . $element->videocode,
								'texturl' => $element->url
							])
						@elseif($element->type == 'image')
							@include('includes.components.form.imageeditor', [
								'title' => $element->title,
								'image' => url('storage/app/public/' . $element->src),
								'imageRemove' => $element->src
							])

							@php
								$images[] = $element->src;
							@endphp
						@endif
					@endforeach
				@else
					@include('includes.components.form.titleeditor')

					@include('includes.components.form.texteditor', [
						'name' => 'paragraphs[]',
						'class' => 'paragraph',
						'title' => 'Digite o texto do parágrafo...'
					])

					@include('includes.components.form.imageeditor')

					@include('includes.components.form.youtubeeditor')
				@endif

				<input type="hidden" name="images-notice-edit" value="{{ implode(',', $images) }}">
				<input type="hidden" name="images-remove">
			</div>

			<div class="row text-center mt-5">
				<div class="col-md-12">
					<button type="button" class="btn border" data-urlajax="{{ route('panel.notices.component', ['name' => 'form.titleeditor']) }}">Adicionar um Titulo <i class="fas fa-heading"></i></button>

					<button type="button" class="btn border" data-name="paragraphs[]" data-class="paragraph" data-title="Digite o texto do parágrafo..." data-urlajax="{{ route('panel.notices.component', ['name' => 'form.texteditor']) }}">Adicionar parágrafo <i class="fas fa-paragraph"></i></button>

					<button type="button" class="btn border" data-urlajax="{{ route('panel.notices.component', ['name' => 'form.imageeditor']) }}">Adicionar imagem <i class="fas fa-image"></i></button>

					<button type="button" class="btn border" data-urlajax="{{ route('panel.notices.component', ['name' => 'form.youtubeeditor']) }}">Adicionar Video do Youtube <i class="fas fa-video"></i></button>
				</div>
			</div>
		</div>

		<div class="categories" id="tab-categories">
			<div class="accordion">
				@foreach($categories as $category)
					@if($category->subcategories->count() > 0)
					<h3>{{ $category->name }}</h3>
					<div>
						@include('includes.components.form.checkboxes', [
							'name' => 'subcategories[]',
							'title' => 'Sub Categorias de ' . $category->name,
							'values' => $category->subcategories->pluck('name', 'id'),
							'checks' => (isset($notice) ? $notice->subcategories->pluck('id')->all() : null)
						])
					</div>
					@endif
				@endforeach
			</div>
		</div>
	</div>

	<button type="submit" class="btn btn-danger mt-3">Salvar <i class="fas fa-save"></i></button>
</form>