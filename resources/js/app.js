import './bootstrap';
import { createApp } from 'vue';
import VueAxios from 'vue-axios';
import axios from 'axios';
import Toaster from '@meforma/vue-toaster';
import router from './router/index';
import App from './App.vue';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import PrimeVue from "primevue/config";
import 'primevue/resources/themes/saga-blue/theme.css';
import 'primeicons/primeicons.css'
import i18next from "i18next";
import I18NextVue from "i18next-vue";

console.log("Hello world from app.js")

function loggedIn() {
    return localStorage.getItem('token')
}

router.beforeEach((to, from, next) => {
    if(to.matched.some(record => record.meta.requiresAuth)) {
        if (!loggedIn()) {
            next({
                path: '/',
                query: { redirect: to.fullPath },
            })
        }else {
            next()
        }
    }else if (to.matched.some(record => record.meta.guest)) {
        if (loggedIn()) {
            next({
                path: '/home',
                query: { redirect: to.fullPath },
            })
        }else {
            next()
        }
    }else{
        next()
    }
})

//localStorage.removeItem('token')
const locales = {
    pt: {
        operator: {
            title: 'Espaço operador',
            table_box_title: 'Ocupação mesa na sala',
            table: 'Mesa',
            toolbar: {
                one: 'novo pedido',
                two: 'inventario',
                three: 'report',
                four: 'historico pedidos'
            },

            dataTable: {
                two: 'Nome',
                three: 'Mesa',
                four: 'Valor Total',
                six: 'Ação'
            }
        },
        //invetory components
        inventory: {
            title: 'Inventario dos produtos (em tempo real)',
            filter_label: 'Filtro por departamento ',
            dataTable: {
                one: 'Produto',
                two: 'Saldo inicial',
                three: 'Saída',
                four: 'Saldo final'
            }
        },
        //bill history component
        billhistory: {
            title: 'Histórico de pedido',
            filtre: {
                date: 'Filtro'
            },
            dataTable: {
                two: 'Cliente',
                three: 'Mesa',
                six: 'emissão',
                seven: 'Ação'
            }
        },
         //report component
         report: {
            title: 'Report',
            btns: {
                close: 'Fechar jornada',
                print: 'imprimir report'
            },
            dataTable: {
                two: 'Quantidade'
            },
        },

        //bi page
        bi: {
            painels_title: {
                one: 'ANALISE DE VENDAS',
                two: 'ANALISE DE CUSTOS',
                three: 'ANALISE DE DESPESAS'
            }
        },

        //sell inteligence component
        bisell: {
            filters: {
                one: 'Inicio',
                two: 'Fim',
                four: 'Colaborador'
            },
            cards: {
                one: 'Vendo do dia',
                two: 'Mês atual',
                three: 'Mês anterior'
            },
            dataTable: {
                four: 'Venda'
            }
        },

        //cost intelligence component
        bicost: {
            title: 'Analise de Custo e Fornecedore',
            filters: {
                one: 'Produto | Fornecedore',
                two: 'Ano',
                three: 'Mês'
            },
            knob:{
                quantity: 'Quantidade'
            },
            dataTable: {
                two: 'Produto',
                three: 'Fornecedore',
                four: 'Quantidade',
                five: 'Custo',
                six: 'Custo total',
                seven: 'Periódo',
                height: 'Ação'
            }
        },
        //bi expense component
        biexpense: {
            title: 'Analise das Despesas',
            filters: {
                one: 'Produto | item menu',
            },
            dataTable: {
                two: 'Nome',
                four: 'Valor Despesa',
                five: 'Salvou por',
                six: 'Data'
            }
        },
        //waiterpage
        waiterpage: {
            title: 'Espaço serviço na sala',
            icons:{
                two: 'Novo pedido',
                three: 'Reservas'
            },
            modal: {
                title: 'Detalhes do pedido',
                btns: {
                    one: 'Adicionar'
                },
            },
            dataTable: {
                three: 'Valor unitario'
            }
        },
        //sidebar menu
        sidebarmenu: {
            one: 'Caixa',
            two: 'Garçom',
            three: 'Inteligencia de negócio',
            four: 'Gestão de reserva',
            five: 'Criar item do menu',
            six: 'Criar tipo do menu',
            seven: 'Colaboador',
            eight: 'Escala',
            nine: 'Estoque',
            ten: 'Compras',
            eleven: 'Consulte entrega',
            twelve: 'Parametros',
            logout: 'Sair',
            lang_label: 'Língua'
        },
        forms: {
            name: 'Nome',
            first_name: 'Sobrenome',
            tel: 'Celular',
            user_name: "Nome de usuario",
            password: 'Senha',
            placeholder_dept: 'Departamento',
            placeholder_post: 'Poste',
            placeholder_salary: 'Salario | Pagamento',
            placeholder_supplier: 'Fornecedores',
            picture: 'Foto',
            observation: 'Observação',
            canal: 'Canal de reserva',
            n_person: 'N pessoa',
            hour: 'hora',
            measure_unit: 'Unidade de medida',
            description: 'Descrição',
            unit_contain: "Conteudo da unidade",
            min_quantity: 'Quantidade minima',
            requisition_number: 'Numero da requisição',
            product_name: 'Nome do produto',
            product_quantity: 'Quantidade do produto',
            product_cost: 'Custo',
            city: 'Cidade',
            neighborhood: 'Bairro',
            person_id: 'CPF',
            submits: {
                create_reser: 'Confirmar a reserva',
                create: 'Salvar',
                update: 'Atualizar'
            },
            in: 'Entrada',
            out: 'Saída',
            partial: 'Taxa',
            fulltime: 'Pleno',
            price: 'Preço de venda',
            abilitate: 'Abilitar',
            desabilitate: 'Desabilitar'
        },
        stockModals: {
            product_title: 'Novo produto',
            create_fiche: 'Nova ficha técnica',
            delivery_title: 'Nova entrega',
            create_supplier: 'Fornecedor',
            fiche_add: 'Adicionar'
        },
        purchase: {
            toolbar: {
                one: 'Consultar estoque',
                two: 'Salvar despesa',
                three: 'Nova requisição'
            },
            dataTable: {
                two: 'Requerente',
                five: 'A entregar'
            }
        }
    },
    fr: {
        //operator page
        operator: {
            title: 'Espace operateur',
            table_box_title: 'Occupation de tables en salle',
            table: 'Table',
            toolbar: {
                one: 'nouvelle commande',
                two: 'inventaire',
                three: 'rapport',
                four: 'historiques'
            },
            dataTable: {
                two: 'Nom',
                three: 'Table',
                four: 'Total',
                six: 'Action'
            }
        },
        //invetory components
        inventory: {
            title: 'Inventaire des produits (en temps réel)',
            filter_label: 'Filtre par departements',
            dataTable: {
                one: 'Produit',
                two: 'Quantité initial',
                three: 'Sortie',
                four: 'Quantité disponible'
            }
        },
        //bill history component
        billhistory: {
            title: 'Historique des commandes',
            filtre: {
                date: 'Filtre'
            },
            dataTable: {
                two: 'Client',
                three: 'Table',
                six: 'Date',
                seven: 'Action'
            }
        },
        //report component
        report: {
            title: 'Rapport',
            btns: {
                close: 'Fermeture',
                print: 'imprimer rapport'
            },
            dataTable: {
                two: 'Quantité'
            },

        },

         //bi page
         bi: {
            painels_title: {
                one: 'ANALYSE DE VENTES',
                two: 'ANALYSE DE COÛTS',
                three: 'ANALYSE DE PERTES'
            }
        },

        //sell inteligence component
        bisell: {
            filters: {
                one: 'Debut',
                two: 'Fin',
                four: 'Colaborateur'
            },
            cards: {
                one: 'Vente du jour',
                two: 'Mois actuel',
                three: 'Mois antérieur'
            },
            dataTable: {
                four: 'Vente'
            }
        },
        //cost intelligence component
        bicost: {
            title: 'Analise de Coût e Fournisseur',
            filters: {
                one: 'Produit | Fournisseur',
                two: 'Année',
                three: 'Mois'
            },
            knob:{
                quantity: 'Quantité'
            },
            dataTable: {
                two: 'Produit',
                three: 'Fournisseur',
                four: 'Quantité',
                five: 'Coût',
                six: 'Coût total',
                seven: 'Periode',
                height: 'Action'
            }
        },
        //bi expense component
        biexpense: {
            title: 'Analise des pertes',
            filters: {
                one: 'Produit | item menu',
            },
            dataTable: {
                two: 'Produit',
                four: 'Valeur perte',
                five: 'Lancé par',
                six: 'Date'
            }
        },
        //waiterpage
        waiterpage: {
            title: 'Dashboard service en salle',
            icons:{
                two: 'Nouvelle commande',
                three: 'Reservation'
            },
            modal: {
                title: 'Detail de la commande',
                btns: {
                    one: 'Adicioner'
                },
            },
            dataTable: {
                three: 'Valeur unitaire'
            }
        },
        //sidebar menu
        sidebarmenu: {
            one: 'Caisse',
            two: 'Serveur',
            three: "Inteligence d'affaire",
            four: 'Gestion de reservation',
            five: 'Créer item de menu',
            six: 'Créer type de menu',
            seven: 'Colaborateur',
            eight: 'Planning',
            nine: 'Stoque',
            ten: 'Achat',
            eleven: 'Livraison',
            twelve: 'Réglage',
            logout: 'Se deconnecter',
            lang_label: 'Langue'
        },
        forms: {
            name: 'Nom',
            first_name: 'Prénom',
            tel: 'Telephone',
            user_name: "Nom d'ultilisateur",
            password: 'Mots de passe',
            placeholder_dept: 'Departement',
            placeholder_post: 'Poste',
            placeholder_salary: 'Salaire | paiement',
            placeholder_supplier: 'Selection de fournisseur',
            picture: 'Photo',
            observation: 'Observation',
            canal: 'Canal de reservation',
            n_person: 'N personne',
            hour: 'heur',
            measure_unit: 'Unité de mésure',
            description: 'Descrition',
            unit_contain: "Contenu de l'unité",
            min_quantity: 'Quantité minimum',
            requisition_number: 'Numero de la requisition',
            product_name: 'Nom du produit',
            product_quantity: 'Quantité du produit',
            product_cost: 'Coût',
            city: 'Ville',
            neighborhood: 'Quartier',
            person_id: 'Numero ID national',
            submits: {
                create_reser: 'Confirmer la reservation',
                create: 'Enregistrer',
                update: 'Actualiser'
            },
            in: 'Entrée',
            out: 'Sortie',
            partial: 'Extra',
            fulltime: 'Plein temps',
            price: 'Prix de vente',
            abilitate: 'Habiliter',
            desabilitate: 'Deshabiliter'
        },
        stockModals: {
            product_title: 'Nouveau produit',
            create_fiche: 'Nouvelle fiche technique',
            delivery_title: 'Nouvelle livraison',
            create_supplier: 'Fournisseur',
            fiche_add: 'Adicionner'
        },
        purchase: {
            toolbar: {
                one: 'Consulter stock',
                two: 'Enregistrer pertes',
                three: 'Nouvelle requisition'
            },
            dataTable: {
                two: 'Requerent',
                five: 'Prevision de livraison'
            }
        }
    },
}
const language = localStorage.getItem('lang') !== null ? localStorage.getItem('lang') : 'pt';
i18next.init({
    lng: language,
    fallback: 'pt',
    witeList: ['pt', 'fr'],
    resources: {
        pt: {translation: locales.pt },
        fr: {translation: locales.fr}
    }
});

createApp(App).use(router).use(VueAxios, axios).use(Toaster, {
    position: 'top'
}).use(VueSweetalert2).use(PrimeVue).use(I18NextVue, {i18next}).mount('#app')
