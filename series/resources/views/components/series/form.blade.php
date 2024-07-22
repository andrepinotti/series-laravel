<form action="{{ $action }}" method="POST">
    @csrf

    @isset($update)
        @method('PUT')
    @endisset

    <div class="mb-3">  
        <label for="nome" class="form-label">Nome: </label>
        <input required type="text" id="nome" 
            name="nome" 
            class="form-control" 
            @isset($nome)
                value="{{ $nome }}"                
            @endisset>
    </div>

    <button class="btn btn-primary">Adicionar</button>


</form>