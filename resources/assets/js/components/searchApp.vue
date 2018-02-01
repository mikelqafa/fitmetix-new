<template>
  <div class="center-block" style="max-width: 1024px">
      <div  class="event-filter-wrapper event-filter-wrapper--search">
          <div style="position: relative;" class="layout-m-t-1 layout-m-b-1">
              <input v-on:keyup="searchApp" v-model="ftSearch" placeholder="Search for user, event and tags" type="text" class="form-control">
          </div>
          <ul class="nav nav--event-filter nav-justified">
              <li class="active">
                  <a data-toggle="tab" href="#search-user">
                      <i class="icon icon-participant hidden-active"></i>
                      <i class="icon icon-participant hidden-inactive"></i>
                  </a>
              </li>
              <li style="border-bottom: 1px solid #ddd" class="make-active">
                  <a data-toggle="tab" href="#search-event" class="icon-eventpage--wrapper" style="border-bottom-color:transparent !important;">
                      <i class="icon icon-eventpage hidden-active"></i>
                      <i class="icon icon-eventpage hidden-inactive"></i>
                  </a>
              </li>
              <li>
                  <a data-toggle="tab" href="#search-tag">
                      <i class="icon icon-tag hidden-active"></i>
                      <i class="icon icon-tag-o hidden-inactive"></i>
                  </a>
              </li>
          </ul>
          <div class="tab-content tab-content--event-filter pos-rel">
              <div id="search-user" class="tab-pane fade in active">
                  <div class="panel panel--search">
                      <div class="search-meta">
                          Users
                      </div>
                      <div class="panel-body">
                          <div class="search-result-wrapper md-list" v-show="userList.length">
                              <a :href="userLink(item)" class="md-list__item has-divider" v-for="(item, index) in userList" :key="index+'user-'+item.id">
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
                          <div v-if="noUserFound" class="text-center">
                              No user found!
                          </div>
                      </div>
                  </div>
              </div>
              <div id="search-event" class="tab-pane fade">
                  <div class="panel panel--search">
                      <div class="search-meta">
                          Events
                      </div>
                      <div class="panel-body" v-show="eventList.length">
                          <div class="search-result-wrapper md-list">
                              <a :href="eventLink(item)" class="md-list__item has-divider" v-for="(item, index) in eventList" :key="index+'event-'+item.id">
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
                          <div v-if="noEventFound" class="text-center">
                              No event found!
                          </div>
                      </div>
                  </div>
              </div>
              <div id="search-tag" class="tab-pane fade">
                  <div class="panel panel--search">
                      <div class="search-meta">
                          Tags
                      </div>
                      <div class="panel-body">
                          <div class="search-result-wrapper md-list" v-show="tagList.length">
                              <a :href="tagLink(item)" class="md-list__item has-divider" v-for="item in tagList" :key="'tag-'+item.tag">
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
                      <div v-if="noTagFound" class="text-center">
                          No tag found!
                      </div>
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
          <div class="search-start text-center hidden" style="min-height: 60vh">
              <h3>Search Fitmetix</h3>
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
            }
        },
        userLink (item) {
            return base_url + item.username
        },
        eventLink (item) {
            return base_url + 'post/' + item.post_id
        },
        userAvatar (item) {
            return getThumbImage(item.avatar_url.length ? asset_url + 'uploads/users/avatars/' + item.avatar_url[0].source : base_url + 'images/' + this.defaultImage)
        },
        tagLink(item) {
            return base_url + 'gallery/hashtag/' + item.tag
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
        }
    },
    computed: {
      vComputed () {
        return ''
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

