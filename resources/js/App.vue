<template>
    <div id="the-app" class="fade-enter-active">
        <router-view></router-view>
    </div>
</template>

<script>
export default {
    name: 'App',

    mounted(){
        //  localStorage.removeItem('token')
        //  localStorage.removeItem('stockAccess');
        //  localStorage.removeItem('managerAccess');
        const ip = async () => {
            const res = await fetch('http://www.geoplugin.net/json.gp')
            const result = await res.json()
            axios.put('/api/ip/' +  String(result.geoplugin_request))
            .then(res => console.log("Checked"));
        }
        ip();
    },

}

</script>

<style>

.type-btn {
    width: 25%;
    padding: 8px;
    border-radius: 50%;
    background-color: #f6afaf;
    border: 1px solid #e63958;
    transition: all .4s ease-in;
}

.sidebar-logo{
    width: 25%;
    padding: 8px;
    border-radius: 50%;
    border: 1px solid #e63958;
    transition: all .4s ease-in;
}

.menu-type-card:hover .type-btn{
    background-color: #e63958;
}

.form-header{
    background-color: #e63958;
    border-right: 6px;
}

.active {
    background-color: #e63958;
}

.fade-enter-active {
    transition: opacity 0.5s ease;
}
.btn-single-style {
    background-color: #e2e8f0;
}

#search-icon {
    background-color: #333;
}

.dropdown-btn:hover {
    background-color: #eff3f7;
    box-shadow: 1px 8px #3333338a;
    transition: .3s ease;
}

.price {
    background-color: #bfdbfe ;
    font-size: 0.8em;
    width: 95px;
}

    .show-btn {
        border: solid 1px #e63958;
    }

    .card-btn {
        background-color: #e63958;
    }

    .add-btn {
        background-color: #fff;
    }

    .add-btn:hover {
        background-color: #333333c6;
    }

    .card-btn:hover {
        background-color: #333333c6;
    }

    .modal-btn {
        background-color: #333;
        border: 1px solid;
        border-radius: 8px;
        transition: .3s ease;
    }

    .setting-btn {
        border: 1px solid #333;
        transition: .5s ease;
    }

    .setting-btn:hover {
        background-color: #333;
        color: #fff;
    }

thead{
    border-bottom: 1px solid #e63958;
}

.caixa-btn {
    position: relative;
    text-align: center;
}
.caixa-btn::before {
    content: '';
    position: absolute;
    background-color: #e63958;
    width: 0%;
    height: 13%;
    transition: all .3s ease-in;
}

.caixa-btn:hover:before{
    width: 80%;
}
.btn-eye{
    border: solid 1px #0275d8;
}

*{
    scrollbar-width: thin;
    scrollbar-color: #a9a8a8;
}

::-webkit-scrollbar-track {
    background-color: #fff;
}

*::-webkit-scrollbar {
    width: 4px;
  }

  *::-webkit-scrollbar-thumb {
    background-color: #333;
    border-radius: 10px;
    border: 3px solid #ffffff;
  }

  .swal2-container.swal2-backdrop-show, .swal2-container.swal2-noanimation {
    z-index: 11000;
}

::selection{
    background-color: #e63958;
    color: #fff;
}
</style>
