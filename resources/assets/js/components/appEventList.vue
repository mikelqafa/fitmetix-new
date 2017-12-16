<template>
    <div>
        <div class="hidden-sm hidden-xs ft-filter">
            <fieldset class="form-group required " style="margin-left: 0">
                <input class="form-control" id="filter-location-input" autocomplete="off" placeholder="By Location" name="location" type="text" style="position: relative; overflow: hidden;">
            </fieldset>
            <fieldset class="form-group required ">
                <input class="form-control" name="date" id="filter-date" autocomplete="off" placeholder="By Date" type="text" style="position: relative; overflow: hidden;">
            </fieldset>
            <fieldset class="form-group required ">
                <input class="form-control" id="filter-tag" name="tags" autocomplete="off" placeholder="By Tag" type="text" style="position: relative; overflow: hidden;">
            </fieldset>
            <fieldset class="form-group required " style="margin-right: 0">
                <input class="form-control" id="filter-title" name="title" autocomplete="off" placeholder="By Title" type="text" style="position: relative; overflow: hidden;">
            </fieldset>
        </div>

        <ul class="nav nav--event-filter nav-justified hidden-lg hidden-md">
            <li class="active">
                <a data-toggle="tab" href="#home">
                    <i class="icon icon-location hidden-active"></i>
                    <i class="icon icon-location-o hidden-inactive"></i>
                </a>
            </li>
            <li>
                <a data-toggle="tab" href="#menu1">
                    <i class="icon icon-time hidden-active"></i>
                    <i class="icon icon-time-o hidden-inactive"></i>
                </a>
            </li>
            <li>
                <a data-toggle="tab" href="#menu2">
                    <i class="icon icon-tag hidden-active"></i>
                    <i class="icon icon-tag-o hidden-inactive"></i>
                </a>
            </li>
            <li>
                <a data-toggle="tab" href="#menu3">
                    <i class="icon icon-label hidden-active"></i>
                    <i class="icon icon-label-o hidden-inactive"></i>
                </a>
            </li>
        </ul>

        <div class="hidden-lg hidden-md tab-content tab-content--event-filter">
            <div id="home" class="tab-pane fade in active">
                <input class="form-control" id="filter-location-input-mob" autocomplete="off" placeholder="By Location" name="location" type="text" style="position: relative; overflow: hidden;">
            </div>
            <div id="menu1" class="tab-pane fade">
                <input class="form-control" name="date" id="filter-date-mob" autocomplete="off" placeholder="By Date" type="text" style="position: relative; overflow: hidden;">
            </div>
            <div id="menu2" class="tab-pane fade">
                <input class="form-control" id="filter-tag-mob" name="tags" autocomplete="off" placeholder="By Tag" type="text" style="position: relative; overflow: hidden;">
            </div>
            <div id="menu3" class="tab-pane fade">
                <fieldset class="form-group required " style="margin-right: 0">
                    <input class="form-control" id="filter-title-mob" name="title" autocomplete="off" placeholder="By Title" type="text" style="position: relative; overflow: hidden;">
                </fieldset>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data: function () {
            return {

            }
        }
    }
</script>