<div class="p-6 lg:p-8 bg-white border-b border-gray-200">
    <h1 class="mt-8 text-2xl font-medium text-gray-900">
        Busca la película que desees agregar.
    </h1>

    <form id="search-form">
        <div class="row">
            <div class="col-lg-6">
                <label>Título</label>
                <x-input type="text" class="form-control" id="title" name="title" />
            </div>
            <div class="col-lg-2">
                <x-button type="button" class="btn btn-info" id="search_btn" onclick="searchMovies()">{{ __('Buscar') }}</x-button>
            </div>
        </div>
    </form>
</div>

<div id="result-search" class="row"></div>
