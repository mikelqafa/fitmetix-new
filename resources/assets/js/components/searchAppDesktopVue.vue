<template>
    <div class="desktop-search">
        <div style="position: relative;">
            <input v-on:keyup="searchApp" v-model="ftSearch" placeholder="Search for user, event and tags" type="text" class="form-control">
            <a href="javascript:;" class="btn reset-search" v-if="ftSearch !== ''"  @click="resetSearch">
                <i class="icon icon-close"></i>
            </a>
        </div>
        <div class="desktop-search-result" v-if="hasResult">
            <div class="pos-rel">
                <div id="search-user" class="tab-pane--desktop" v-show="userList.length">
                    <div class="search-meta">
                        Users
                    </div>
                    <div class="search-result-wrapper md-list">
                        <a :href="userLink(item)" class="md-list__item " v-for="(item, index) in userList" :key="index+'user-'+item.id">
                            <div class="md-list__item-content">
                                          <span class="md-list__item-icon user-avatar" v-bind:style="{ backgroundImage: 'url(' + userAvatar(item) +')'}">
                                          </span>
                                <div class="md-list__item-primary">
                                    <span>{{item.username}}</span>
                                    <div class="md-list__item-text-body">{{item.name}}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div id="search-event" class="tab-pane---desktop" v-show="eventList.length">
                    <div class="search-meta">
                        Events
                    </div>
                    <div class="search-result-wrapper md-list">
                        <a :href="eventLink(item)" class="md-list__item " v-for="(item, index) in eventList" :key="index+'event-'+item.id">
                            <div class="md-list__item-content">
                                          <span class="md-list__item-icon user-avatar">
                                              <i class="icon icon-eventpage"></i>
                                          </span>
                                <div class="md-list__item-primary">
                                    <span>{{item.name}}</span>
                                    <div class="md-list__item-text-body">{{item.about}}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div id="search-tag" class="tab-pane--desktop" v-show="tagList.length">
                    <div class="search-meta">Tags</div>
                    <div class="search-result-wrapper md-list" v-show="tagList.length">
                        <a :href="tagLink(item)" class="md-list__item " v-for="item in tagList" :key="'tag-'+item.tag">
                                      <span class="md-list__item-content">
                                          <span class="md-list__item-icon">
                                              <i class="icon icon-tag"></i>
                                          </span>
                                          <span class="md-list__item-primary">
                                              {{item.tag}}
                                          </span>
                                      </span>
                        </a>
                    </div>
                </div>
                <div class="absolute-loader" v-if="isLoading">
                    <div class="ft-loading ft-loading--abs">
                        <span class="ft-loading__dot"></span>
                        <span class="ft-loading__dot"></span>
                        <span class="ft-loading__dot"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
  export default {
    data: function () {
      return {
          ftSearch: '',
          userList: [],
          tagList: [],
          eventList: [],
          isLoading: false,
          noEventFound: false,
          noUserFound: false,
          noTagFound: false
      }
    },
    methods: {
        searchApp: function (e) {
            if(e.target.value != '') {
                this.ftSearch = e.target.value
                this.getList()
            } else {
                this.userList = []
                this.tagList = []
                this.eventList = []
            }
        },
        userLink (item) {
            return base_url + item.username
        },
        userAvatar (item) {
            return getThumbImage(item.avatar_url.length ? asset_url + 'uploads/users/avatars/' + item.avatar_url[0].source : base_url + 'images/' + this.defaultImage)
        },
        tagLink(item) {
            return base_url + 'gallery/hashtag/' + item.tag
        },
        eventLink (item) {
            return base_url + 'post/' + item.post_id
        },
        getList: function () {
            let that = this
            let _token = $("meta[name=_token]").attr('content')
            this.isLoading = true
            this.userList = []
            this.tagList = []
            this.eventList = []
            axios({
                method: 'post',
                responseType: 'json',
                url: base_url + 'ajax/search',
                data: {
                    keyword: that.ftSearch,
                    _token: _token
                }
            }).then(function (response) {
                that.isLoading = false
                that.userList = []
                that.tagList = []
                that.eventList = []
                if (response.status == 200) {
                    let noEventFound = true
                    for(let i = 0;i<response.data[0].events.length; i++) {
                        that.eventList.push(response.data[0].events[i])
                        noEventFound = false
                    }
                    that.noEventFound = noEventFound
                    let noTagFound = true
                    for(let i = 0;i<response.data[0].tags.length; i++) {
                        that.tagList.push(response.data[0].tags[i])
                        noTagFound = false
                    }
                    that.noTagFound = noTagFound
                    let noUserFound = true
                    for(let i = 0;i<response.data[0].users.length; i++) {
                        that.userList.push(response.data[0].users[i])
                        noUserFound = false
                    }
                    that.noUserFound = noUserFound
                }
            }).catch(function (error) {
                console.log(error)
            })
        },
        resetSearch: function () {
            this.ftSearch = ''
            this.userList = []
            this.tagList = []
            this.eventList = []
        }
    },
    computed: {
      hasResult () {
        return this.userList.length || this.eventList.length || this.tagList.length || this.isLoading
      }
    },
    mounted () {
    }
  }
</script>
<style>
    .flip-list-move {
        transition: transform 1s;
    }
</style>

