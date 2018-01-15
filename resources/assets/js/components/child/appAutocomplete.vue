<template>
    <div>
        <div style="position:relative">
            <input v-on:focus="onFocus" placeholder="Send to" v-on:blur="onBlur" class="form-control" type="text" v-model="autoCompleteValue" @keydown.enter='onKeyEnter' @keydown.down='onKeyDown' @keydown.up='onKeyUp' @input='onInput'/>
            <div v-if="suggestions.length" class="dropdown-menu md-list md-list--autocomplete md-list--dense" v-bind:class="{'md-list--open':openSuggestion}">
                <a href="javascript:;" class="md-list__item" v-for="(suggestion, index) in filterSuggestion" v-bind:class="{'md-list__item--active': isActive(index)}" @click="suggestionClick(index)">
                    <div :title="'@'+suggestion.name" class="md-list__item-icon user-avatar" :style="{ backgroundImage: 'url('+ getThumbImage(suggestion.avatar) +')' }"></div>
                    <div class="md-list__item-content">
                        <div class="md-list__item-primary">
                            <div class="user-name user ft-user-name">
                                {{ suggestion.name }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="avatar-list md-layout md-layout--wrap" v-show="selections.length">
            <div v-for="(item, index) in selections" class="margin-1" :key="item.name">
                <div class="avatar-list__item" :style="{ backgroundImage: 'url('+ getThumbImage(item.avatar) +')' }" :title="item.name">
                    <a href="javascript:;" class="close-btn" title="remove" @click="removeFromSelections(index)">
                        <i class="icon icon-close"></i>
                    </a>
                </div>
                <div class="text-center">
                    {{item.name}}
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    .avatar-list {
        padding: 4px;
        min-height: 40px;
        margin-top: 4px;
    }
    .avatar-list .avatar-list__item {
        width: 40px;
        height: 40px;
        background-size: cover;
        background-position: center;
        border-radius: 50%;
        margin: 4px;
        position: relative;
        background-color: #007E83;
    }
    .avatar-list .close-btn {
        position: absolute;
        top: 0px;
        right: -6px;
        width: 20px;
        height: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        line-height: 20px;
        text-align: center;
    }
</style>
<script>
    export default {
        data() {
            return {
                open: false,
                current: 0,
                autoCompleteValue: '',
                selections: [],
                suggestions: [],
                focus: false
            }
        },

        computed: {
            openSuggestion() {
                return this.suggestions.length && this.open && this.focus
            },
            filterSuggestion: function () {
                let that = this
                return this.suggestions.filter(function (item) {
                    let matched = false
                    for(let i=0; i< that.selections.length;i++) {
                        if(item.name === that.selections[i].name) {
                            matched = true
                            break
                        }
                    }
                    return !matched
                })
            }
        },

        methods: {
            getThumbImage: function (url) {
                let url_arr = url.split('/');
                let last_string = url_arr[url_arr.length - 1]
                return url.replace(last_string, '100_' + last_string)
            },
            onKeyEnter: function() {
                // this.autoCompleteValue = this.matches[this.current];
                this.selections.push(this.suggestions[this.current])
                this.suggestions = []
                this.autoCompleteValue = ''
            },
            removeFromSelections: function (i) {
              this.selections.splice(i,1)
            },
            onFocus: function () {
              this.focus = true
            },
            onBlur: function () {
              this.focus = false
            },
            onKeyUp: function() {
                if(this.autoCompleteValue == '') {
                    return
                }
                if (this.current > 0)
                    this.current--
            },
            onKeyDown: function() {
                if(this.autoCompleteValue == '') {
                    this.suggestion = []
                    return
                }
                if (this.current < this.suggestions.length - 1)
                    this.current++
            },
            isActive: function(index) {
                return index === this.current
            },
            onInput: function() {
                console.log('input')
                if (this.open == false) {
                    this.open = true
                    this.current = 0
                }
                this.fetchUser(this.autoCompleteValue)
            },
            suggestionClick(index) {
                this.selections.push(this.suggestions[index])
                this.suggestions = []
                this.autoCompleteValue = ''
            },
            fetchUser: function (data) {
                if(data == '') {
                    return
                }
                let that = this
                axios({
                    method: 'get',
                    responseType: 'json',
                    url: base_url + 'ajax/get-users-mentions',
                    params: {
                        query: data,
                        limit: 5
                    }
                }).then( function (response) {
                    that.suggestions = []
                    if(response.status == 200) {
                        for(let i=0; i<response.data.length; i++) {
                            that.suggestions.push({avatar: response.data[i].image, name: response.data[i].username})
                        }
                    }
                }).catch(function(error) {
                    console.log(error)
                })
            },
            reset: function () {
                this.selections = []
            }
        }
    }
</script>
