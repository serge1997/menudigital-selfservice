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
                title_1: 'ANALISE DE VENDAS',
                title_2: 'ANALISE DE CUSTOS',
                title_3: 'ANALISE DE DESPESAS'
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
                one: 'Vente du jour',
                two: 'Mois actuel',
                three: 'Mois antérieur'
            }
        },

        //cost intelligence component
        bicost: {
            title: 'Analise de Custo e Fornecedore',
            filters: {
                one: 'Produto | Fornecedore',
                two: 'Année',
                three: 'Mois'
            },
            knob:{
                quantity: 'Quantidade'
            },
            dataTable: {
                two: 'Produto',
                three: 'Fornecedore',
                four: 'Quantdade',
                five: 'Custo',
                six: 'Custo total',
                seven: 'Periódo',
                height: 'Ação'
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
                four: 'Quantité final'
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
        }
    }
}

i18next.init({
    lng: 'fr',
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
