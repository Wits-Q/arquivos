<x-app-layout>
    <x-slot name="header">
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
            {{ __('Lista de Atendimentos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6 bg-white border-b border-gray-200 flex justify-between items-center">
                    <!-- Área de pesquisa de pacientes -->
                    <div class="w-1/4 block">
                    <form action="{{ route('dashboard') }}" method="GET">
                        <input type="text" id="searchInput3" name="search"
                        class="border-gray-300 rounded-md px-4 py-2 w-full bg-white border shadow-sm border-slate-300 
                        placeholder-slate-400 focus:outline-none focus:border-green-800 focus:ring-green-800 block 
                        w-full rounded-md sm:text-sm focus:ring-1 focus:ring-opacity-50" placeholder="Nome ou Cartão SUS...">

                        <button class="d-none" type="submit">Search</button>
                    </form>
                        
                    </div>
                    <!-- Ver somente meus atendimentos switch -->
                    <div class="">
                        
                        <form class="flex items-center ml-2" id="dashboardForm" action="{{ route('dashboard') }}" method="GET">
                            <label class="switch flex items-center justify-center">
                                <input type="checkbox" id="switchVerAtendimentos" 
                                onchange="meusEncaminhamentos('{{\Illuminate\Support\Facades\Auth::user()->attention_type}}')">
                                <span class="slider"></span>
                            </label>
                            <span class="ml-2 text-gray-800">Ver somente meus atendimentos</span>
                        </form>
                        
                    </div>
                                        <!-- Botão Novo paciente -->
                    <button type="button" class=" icon text-white font-bold py-2 px-4 rounded shadow-md" style="background-color: #186f65;" data-toggle="modal" data-target="#modalNovoPaciente">
                        Novo Paciente
                        
                    </button>


                    <!-- Modal -->
                    

                    @include('modaleditar')
                    
                    
                    

                </div>
            </div>
            <!-- Tabela de Atendimentos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200 d-flex flex-column flex">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="w-1/5 px-20 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Prioridade
                                </th>
                                <th scope="col" class="w-1/5 px-20 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nome
                                </th>
                                <th scope="col" class="w-1/5 px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Identificação SUS
                                </th>
                                <th scope="col" class="w-1/5 px-20 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Data
                                </th>
                                <th scope="col" class="w-1/5 px-20 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ver mais
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 d-flex flex-column w-full" style="width: 100%!important;">
                            @foreach($atendimentos as $atendimento)
                                @include('components.table-row')
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    

    <script>
        // Seleciona o campo de entrada de texto
        const searchInput = document.getElementById('searchInput');

        // Adiciona um evento de escuta para o evento de input (quando o usuário digita algo)
        searchInput.addEventListener('input', function() {
            // Obtém o valor digitado pelo usuário e converte para minúsculas para tornar a pesquisa insensível a maiúsculas e minúsculas
            const searchText = searchInput.value.toLowerCase();

            // Seleciona todas as linhas de atendimento
            const atendimentos = document.querySelectorAll('.row');

            // Itera sobre cada atendimento
            atendimentos.forEach(function(atendimento) {
                // Obtém todo o texto dentro do atendimento
                const atendimentoText = atendimento.textContent.toLowerCase();

                // Verifica se o texto de pesquisa está presente em alguma parte do atendimento
                if (containsText(atendimentoText, searchText)) {
                    // Se estiver presente, mostra o atendimento
                    atendimento.style.display = '';
                } else {
                    // Se não estiver presente, esconde o atendimento
                    atendimento.style.display = 'none';
                }
            });
        });

        // Função auxiliar para verificar se uma string contém o texto de pesquisa
        function containsText(atendimentoText, searchText) {
            // Verifica se o texto de pesquisa está presente no texto do atendimento
            return atendimentoText.includes(searchText);
        }

        function meusEncaminhamentos(tipoUsuario) {
            const checkbox = document.getElementById('switchVerAtendimentos');
            checkbox.addEventListener('change', function() {
                const form = document.getElementById('dashboardForm');
                const tipoUsuarioAtual = '{{ \Illuminate\Support\Facades\Auth::user()->attention_type }}'; // Obtenha o tipo de usuário de onde for apropriado

                // Verifica se o switch está marcado
                if (checkbox.checked) {            
                    // Se estiver marcado, filtra apenas os atendimentos encaminhados para o tipo de usuário atual
                                form.action = '{{ route('dashboard', ['search' => '']) }}' + tipoUsuarioAtual;
                } else {           
                    // Se não estiver marcado, volta para a rota padrão sem filtro
                                form.action = "{{ route('dashboard') }}";
                }            
                        form.submit();
            });
        }

        // function meusEncaminhamentos(tipoUsuario) {
        //     const checkbox = document.getElementById('switchVerAtendimentos');
        //     checkbox.addEventListener('change', function() {
        //         const form = document.getElementById('dashboardForm');
        //         const tipoUsuario = '{{\Illuminate\Support\Facades\Auth::user()->attention_type}}'; // Obtenha o tipo de usuário de onde for apropriado

        //         // Verifica se o switch está marcado
        //         if (checkbox.checked) {            
        //             // Se estiver marcado, filtra apenas os atendimentos encaminhados para o tipo de usuário atual
        //             form.action = '{{ route('dashboard', [ 'search' => ''])}}' + tipoUsuario;
        //         } else {           
        //             // Se não estiver marcado, volta para a rota padrão sem filtro
        //             form.action = "{{ route('dashboard') }}";
        //         }            
        //         form.submit();
        //     });
        // }


        // function meusEncaminhamentos(tipoUsuario) {
        //     const form = document.getElementById('dashboardForm');
        //     const checkbox = document.getElementById('switchVerAtendimentos');

        //     if (tipoUsuario) {            
               
        //         form.action = '{{ route('dashboard', [ 'search' => ' + tipoUsuario + '])}}';

        //     } else {           
        //         form.action = "{{ route('dashboard') }}";
        //     }            
        //     form.submit();
        // }

        function converteData(params) {
            var partes = params.split("-");
            return partes[2] + "-" + partes[1] + "-" + partes[0];
        }
        function resgatarDados(atendimento) {
            atendimento = JSON.parse(atendimento)
            

            document.getElementById('nome').value = atendimento.nome;
            document.getElementById('idade').value = atendimento.idade;
            console.log(atendimento.data_nascimento)
            if(atendimento.sexo && atendimento.sexo === 'masculino') {
                document.getElementById('masculino').setAttribute('checked', true);
            }else{
                document.getElementById('feminino').setAttribute('checked', true);
            }

            document.getElementById('contato').value = atendimento.contato;
            document.getElementById('data-nascimento').value = atendimento.data_nascimento;
            document.getElementById('cartao-sus').value = atendimento.cartao_sus;
            document.getElementById('endereco').value = atendimento.endereco;
            document.getElementById('data-cadastro').value = atendimento.data_cadastro;
            document.getElementById('ubs').value = atendimento.ubs;
            document.getElementById('acs').value = atendimento.acs;
            document.getElementById('diagnostico').value = atendimento.diagnostico;
            document.getElementById('comorbidades').value = atendimento.comorbidades;
            document.getElementById('ultima-internacao').value = atendimento.ultima_internacao;
            // document.getElementById('responsavel_cadastro').value = atendimento.responsavel_cadastro;
            document.getElementById('medico-responsavel').value = atendimento.medico_responsavel;

            if(atendimento.prioridade && atendimento.prioridade === 'alta') {
                document.getElementById('alta').setAttribute('checked', true);
            }else if(atendimento.prioridade === 'media'){
                document.getElementById('media').setAttribute('checked', true);
            }else{
                document.getElementById('baixa').setAttribute('checked', true);
            }

            if(atendimento.neurologicas) {
                document.getElementById('neurologicas').setAttribute('checked', true)
            }
        

            if (atendimento.neurologicas) {
                document.getElementById('neurologicas').setAttribute('checked', true);
            }


            if (atendimento.dor_descricao) {
                document.getElementById('dor').setAttribute('checked', true);
            }
            document.getElementById('dor_descricao').value = atendimento.dor_descricao;

            if (atendimento.incapacidade) {
                document.getElementById('incapacidade').setAttribute('checked', true);
            }
            document.getElementById('avds').value = atendimento.incapacidade_descricao;

            // Aplicando a mesma lógica com if para os elementos restantes
            if (atendimento.osteomusculares) {
                document.getElementById('osteomusculares').setAttribute('checked', true);
            }
            document.getElementById('motivos-osteomusculares').value = atendimento.osteomusculares_descricao;

            
        
            if (atendimento.uroginecologicas) {
                document.getElementById('uroginecologicas').setAttribute('checked', true);
            }
            document.getElementById('motivos-uroginecologicas').value = atendimento.uroginecologicas_descricao;

            
            
            
            if (atendimento.cardiovasculares) {
                document.getElementById('cardiovasculares').setAttribute('checked', true);
            }
            document.getElementById('motivos-cardiovasculares').value = atendimento.cardiovasculares_descricao;

            
            
            if (atendimento.respiratorias) {
                document.getElementById('respiratorias').setAttribute('checked', true);
            }
            document.getElementById('motivos-respiratorias').value = atendimento.respiratorias_descricao;

            
            
            
            if (atendimento.oncologicas) {
                document.getElementById('oncologicas').setAttribute('checked', true);
            }
            document.getElementById('motivos-oncologicas').value = atendimento.oncologicas_descricao;

            
            
            
            if (atendimento.pediatria) {
                document.getElementById('pediatria').setAttribute('checked', true);
            }
            document.getElementById('motivos-pediatria').value = atendimento.pediatria_descricao;

            if (atendimento.pediatria) {
                document.getElementById('multiplas').setAttribute('checked', true);
            }
            document.getElementById('motivos-deficiencias').value = atendimento.pediatria_descricao;

            

            document.getElementById('queixa').value = atendimento.queixa;


            document.getElementById('achados-exame-fisico').value = atendimento.achados_exame_fisico;


            document.getElementById('testes-padronizados').value = atendimento.testes_padronizados;


            document.getElementById('condicao-funcional').value = atendimento.condicao_funcional;


            document.getElementById('fatores-ambientais').value = atendimento.fatores_ambientais;


            document.getElementById('diagnostico-fisioterapeutico').value = atendimento.diagnostico_fisioterapeutico;


            document.getElementById('atividade_movase').value = atendimento.mova_se;


            document.getElementById('atividade_mdms').value = atendimento.menos_dor_mais_saude;


            document.getElementById('atividade_peso').value = atendimento.peso_saudavel;


            document.getElementById('atividade_geracao').value = atendimento.geracao_esporte;


            document.getElementById('atividade_nda').value = atendimento.nda;


            document.getElementById('passadas_movase').value = atendimento.mova_se_participou;


            document.getElementById('passadas_mdms').value = atendimento.mais_saude_participou;


            document.getElementById('passadas_peso').value = atendimento.peso_saudavel_participou;


            document.getElementById('passadas_geracao').value = atendimento.geracao_esporte_participou;


            document.getElementById('passadas_npa').value = atendimento.nda_participou;


            document.getElementById('condicao-funcional-sec').value = atendimento.funcional_condicao;


            document.getElementById('fatores-ambientais-sec').value = atendimento.tratamento_ofertado;


            document.getElementById('diagnostico-fisioterapeutico-sec').value = atendimento.evolucao_funcional;


           // Supondo que 'atendimento.sexo' contenha o sexo do atendimento

            if (atendimento.sessoes) {
                // Separa as sessões em um array
                var sessoes = atendimento.sessoes.split(',');
                
                // Itera sobre cada sessão
                sessoes.forEach(function(sessao) {
                    // Verifica o valor da sessão e marca o checkbox correspondente
                    switch (sessao.trim()) {
                        case 'Menos_de_10':
                            document.getElementById('sessoes_menos_de_10').setAttribute('checked', true);
                            break;
                        case '10_à_20':
                            document.getElementById('sessoes_10_20').setAttribute('checked', true);
                            break;
                        case '20_à_30':
                            document.getElementById('sessoes_20_30').setAttribute('checked', true);
                            break;
                        case 'Mais_de_30':
                            document.getElementById('sessoes_mais_de_30').setAttribute('checked', true);
                            break;
                        default:
                            break;
                    }
                });
            }

            // Supondo que 'atendimento.assiduidade' contenha a assiduidade do paciente

            if (atendimento.assiduidade) {
                // Verifica o valor de 'atendimento.assiduidade' e marca o radio button correspondente
                switch (atendimento.assiduidade) {
                    case 'assiduo':
                        document.getElementById('assiduo_check').setAttribute('checked', true);
                        break;
                    case 'nao_assiduo':
                        document.getElementById('nao_assiduo_check').setAttribute('checked', true);
                        break;
                    default:
                        break;
                }
            }

            document.getElementById('fatores_sec').value = atendimento.ambientais_pessoais;


            document.getElementById('diagnostico_sec').value = atendimento.diagnostico_fisio;


            document.getElementById('criterios_sec').value = atendimento.criterios;


            document.getElementById('justificativa_sec').value = atendimento.justificativa;

            
        let modal = document.getElementById('modaleditar');
        
        }
        
    </script>


    <!-- CSS para o switch -->
    <style>
        .switch {
            display: flex;
            align-items: center;
            cursor: pointer;
            margin-bottom: -0.5px;
        }

        .switch input {
            display: none;
        }

        .slider {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
            background-color: #ccc;
            border-radius: 12px;
            transition: background-color 0.3s;
            margin-right: 8px; /* Adiciona espaço à direita do switch */
        }

        .slider:before {
            content: "";
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 4px;
            width: 16px;
            height: 16px;
            background-color: white;
            border-radius: 50%;
            transition: transform 0.3s;
        }

        input:checked + .slider {
            background-color: #186f65;
        }

        input:checked + .slider:before {
            transform: translate(26px, -50%);
        }

        .switch-text {
            color: #333; /* Cor do texto */
            font-size: 14px; /* Tamanho da fonte */
        }

        td.name {
            white-space: nowrap; /* Impede que o texto quebre em várias linhas */
            overflow: hidden; /* Oculta o conteúdo que excede a largura da célula */
            text-overflow: ellipsis; /* Exibe reticências (...) quando o conteúdo é truncado */
        }

        td.date {
            font-family: system-ui; /* Define a fonte */
            color: #333; /* Define a cor do texto */
            font-size: 15.5px; /* Define o tamanho da fonte */
            background-color: transparent; /* Define a cor de fundo */
            padding: 5px 10px; /* Adiciona espaço interno */
            border-radius: 5px; /* Adiciona borda arredondada */
        }


    </style>

</x-app-layout>
