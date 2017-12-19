<template>
    <div style="position:relative">
        <input v-on:focus="onFocus" v-on:blur="onBlur" class="form-control" type="text" v-model="autoCompleteValue" @keydown.enter='onKeyEnter' @keydown.down='onKeyDown' @keydown.up='onKeyUp' @input='onInput'/>
        <div class="dropdown-menu md-list md-list--autocomplete md-list--dense" v-bind:class="{'md-list--open':openSuggestion}">
            <a href="javascript:;" class="md-list__item" v-for="(suggestion, index) in suggestions" v-bind:class="{'md-list__item--active': isActive(index)}" @click="suggestionClick(index)">
                <div title="@prakash" class="md-list__item-icon user-avatar" :style="{ backgroundImage: 'url('+ suggestion.avatar +')' }"></div>
                <div class="md-list__item-content">
                    <div class="md-list__item-primary">
                        <div href="http://localhost/fitmetix/public/mikele" title="@prakash" class="user-name user ft-user-name">
                            {{ suggestion.name }}
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="md-layout md-layout--wrap">
            
        </div>
    </div>
</template>

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
            }
        },

        methods: {
            onKeyEnter: function() {
                // this.autoCompleteValue = this.matches[this.current];
                this.selections.push(this.suggestions[this.current])
                this.open = false
                this.autoCompleteValue = ''
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
                    this.open = false
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
                let selectedData = this.matches[index]
                this.selections.push(selectedData)
                this.open = false
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
            }
        }
    }
</script>
