<template>
    <div id="the-app" class="fade-enter-active">
        <router-view></router-view>
    </div>
</template>

<script>
export default {
    name: 'App',

    mounted(){
        //localStorage.removeItem('token')
    },

}

</script>

<style>
.menu-type-card {
    border-bottom: 1px #e63958 solid;
}

.type-btn {
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
</style>
