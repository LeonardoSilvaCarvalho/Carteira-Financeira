//Depósito com autenticação via senha
document.addEventListener('DOMContentLoaded', function () {
    // Referencia aos elementos do formualrio de deposito
    const form = document.getElementById('depositForm');
    const amountInput = form.amount;
    const balanceDisplay = document.getElementById('balanceDisplay');
    const animatedCard = document.getElementById('animatedCard');
    const formCard = document.getElementById('formCard');
    const transactionList = document.querySelector('.list-group');

    // Função que exibe prompt para o usuario digitar a senha
    const showPasswordPrompt = (amount) => {
        Swal.fire({
            title: 'Digite sua senha para confirmar o depósito:',
            input: 'password',
            inputPlaceholder: 'Digite a senha',
            inputAttributes: {
                autocapitalize: 'off',
                autocorrect: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar',
            preConfirm: (password) => {
                //Validação: exige senha
                if (!password) {
                    Swal.showValidationMessage('Você precisa digitar sua senha!');
                    return false;
                }

                // Envia os dados para o servidor via fetch
                return fetch('/deposit', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({amount: amount, password: password})
                })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            throw new Error(data.message);
                        }
                        return data;
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`Erro: ${error.message}`);
                    });
            }
        }).then((result) => {
            // Se confirmação for aceita e nao houver erro
            if (result.isConfirmed && result.value) {
                const data = result.value;
                // Atualiza saldo e transações
                balanceDisplay.textContent = `${data.new_balance.toFixed(2).replace('.', ',')}`;
                form.reset();

                if (transactionList) {
                    transactionList.innerHTML = data.transactions_html;
                }

                Swal.fire({
                    title: 'Sucesso!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            }
        });
    };

    // Animações na primeira visita
    if (!document.referrer.includes('/deposit')) {
        animatedCard?.classList.add('animate__fadeInUp');
        formCard?.firstElementChild?.classList.add('animate__fadeInUp', 'animate__delay-1s');
    }

    // Evento de envio do formulário de depósito
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const amount = parseFloat(amountInput.value);
        if (!amount || amount <= 0) return;
        showPasswordPrompt(amount);
    });

    // Permite usar Enter para enviar o formulário
    amountInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            form.querySelector('button[type="submit"]').click();
        }
    });
});

//Transferência com autenticação
document.addEventListener('DOMContentLoaded', function () {
    const transferForm = document.getElementById('transferForm');
    const amountInput = transferForm.amount;
    const toNameInput = transferForm.toName;
    const toAccountInput = transferForm.querySelector('[name="toAccount[number]"]');
    const toAgencyInput = transferForm.toAgency;
    const transactionList = document.querySelector('.lista-transferencia');


    const showPasswordPrompt = (amount, toAccount, toName, toAgency) => {
        Swal.fire({
            title: 'Digite sua senha para confirmar a transferência:',
            input: 'password',
            inputPlaceholder: 'Digite a senha',
            inputAttributes: {
                autocapitalize: 'off',
                autocorrect: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar',
            preConfirm: (password) => {
                if (!password) {
                    Swal.showValidationMessage('Você precisa digitar sua senha!');
                    return false;
                }


                return fetch('/transfer', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        amount: amount,
                        password: password,
                        toAccount: toAccount,
                        toName: toName,
                        toAgency: toAgency
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            throw new Error(data.message);
                        }
                        return data;
                    })
                    .catch(error => {
                        Swal.showValidationMessage(`Erro: ${error.message}`);
                    });
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                const data = result.value;
                transferForm.reset();


                if (transactionList) {
                    transactionList.innerHTML = data.transactions_html;
                }

                Swal.fire({
                    title: 'Sucesso!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            }
        });
    };

    // Envio do formulário de transferência
    transferForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const amount = parseFloat(amountInput.value);
        const toAccount = { number: toAccountInput.value };
        const toName = toNameInput.value;
        const toAgency = toAgencyInput.value;

        // Valida os campos antes de enviar
        if (!amount || amount <= 0 || !toAccount.number || !toName || !toAgency) {
            return Swal.fire('Erro', 'Todos os campos são obrigatórios!', 'error');
        }

        showPasswordPrompt(amount, toAccount, toName, toAgency);
    });
});

//Reversão de transação com senha
function reverseTransaction(transactionId) {
    Swal.fire({
        title: 'Confirme sua senha',
        html: `
            <p>Para reverter esta transação, insira sua senha abaixo:</p>
            <input type="password" id="reversalPassword" class="swal2-input" placeholder="Senha">`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            const password = document.getElementById('reversalPassword').value;
            if (!password) {
                Swal.showValidationMessage('Você precisa inserir sua senha');
            }
            return password;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const password = result.value;

            // Envia a requisição de reversão
            fetch('revers/' + transactionId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({ password })
            })
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        icon: data.success ? 'success' : 'error',
                        title: data.message,
                        showConfirmButton: false,
                        timer: 2000
                    });

                    // Recarrega a página se a reversão foi feita
                    if (data.success) {
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro ao processar reversão.',
                        text: error.message
                    });
                    console.error(error);
                });
        }
    });
}

//Inicializa DataTable
$(document).ready(function () {
    $('#transactionsTable').DataTable({
        pageLength: 10,
        lengthChange: false,
        ordering: false,
        info: false,
        dom: 'tp',
        language: {
            paginate: {
                next: '>',
                previous: '<'
            },
            zeroRecords: "Nenhuma transação encontrada",
        }
    });
});

//Mostrar ou ocultar saldo
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('toggleBalance');
    const icon = document.getElementById('toggleIcon');
    const balanceHidden = document.getElementById('balanceHidden');
    const balanceReal = document.getElementById('balanceReal'); // ou 'balanceDisplay' dependendo da tela

    let visible = false;

    btn.addEventListener('click', function () {
        visible = !visible;

        if (visible) {
            balanceHidden.classList.add('d-none'); // esconde texto com 'R$ ***"
            balanceReal.classList.remove('d-none'); //mostra saldo real
            icon.className = 'bi bi-eye-slash-fill'; //icone de olho fechado
        } else {
            balanceReal.classList.add('d-none'); // esconde saldo real
            balanceHidden.classList.remove('d-none'); // mostra texto escondido
            icon.className = 'bi bi-eye-fill'; // icone de olho aberto
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('toggleBalance');
    const icon = document.getElementById('toggleIcon');
    const balanceHidden = document.getElementById('balanceHidden');
    const balanceReal = document.getElementById('balanceDisplay');

    let visible = false;

    btn.addEventListener('click', function () {
        visible = !visible;

        if (visible) {
            balanceHidden.classList.add('d-none');
            balanceReal.classList.remove('d-none');
            icon.className = 'bi bi-eye-slash-fill';
        } else {
            balanceReal.classList.add('d-none');
            balanceHidden.classList.remove('d-none');
            icon.className = 'bi bi-eye-fill';
        }
    });
});



