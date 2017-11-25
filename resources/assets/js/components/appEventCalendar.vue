<template>
    <div>
        <app-event-calender></app-event-calender>
        <div class="ft-calendar">
            <header>
                <h2 class="ft-calendar-h2">September</h2>
                <a class="btn-prev svg-icon" href="javascript:;">
                    <svg fill="#1E7C82" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.41 16.09l-4.58-4.59 4.58-4.59L14 5.5l-6 6 6 6z"/>
                        <path d="M0-.5h24v24H0z" fill="none"/>
                    </svg>
                </a>
                <a class="btn-next  svg-icon" href="javascript:;">
                    <svg fill="#1E7C82" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.41 16.09l-4.58-4.59 4.58-4.59L14 5.5l-6 6 6 6z"/>
                        <path d="M0-.5h24v24H0z" fill="none"/>
                    </svg>
                </a>
            </header>
            <table class="ft-calendar__table">
                <thead>
                <tr>
                    <td>Mo</td>
                    <td>Tu</td>
                    <td>We</td>
                    <td>Th</td>
                    <td>Fr</td>
                    <td>Sa</td>
                    <td>Su</td>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="prev-month">26</td>
                        <td class="prev-month">27</td>
                        <td class="prev-month">28</td>
                        <td class="prev-month">29</td>
                        <td class="prev-month">30</td>
                        <td class="prev-month">31</td>
                        <td>1</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>3</td>
                        <td>4</td>
                        <td>5</td>
                        <td>6</td>
                        <td>7</td>
                        <td>8</td>
                    </tr>
                    <tr>
                        <td>9</td>
                        <td class="event">10</td>
                        <td>11</td>
                        <td>12</td>
                        <td>13</td>
                        <td>14</td>
                        <td>15</td>
                    </tr>
                    <tr>
                        <td>16</td>
                        <td>17</td>
                        <td>18</td>
                        <td>19</td>
                        <td>20</td>
                        <td class="event">21</td>
                        <td>22</td>
                    </tr>
                    <tr>
                        <td class="current-day event">23</td>
                        <td>24</td>
                        <td>25</td>
                        <td>26</td>
                        <td>27</td>
                        <td>28</td>
                        <td>29</td>
                    </tr>
                    <tr>
                        <td>30</td>
                        <td class="next-month">1</td>
                        <td class="next-month">2</td>
                        <td class="next-month">3</td>
                        <td class="next-month">4</td>
                        <td class="next-month">5</td>
                        <td class="next-month">6</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<style>
    .ft-calendar {
        display: flex;
        flex-direction: column;
        align-items: center;
        max-width: 510px;
        margin: 0 auto;
    }
    .ft-calendar header {
        width: 100%;
    }
    .ft-calendar-h2 {
        font-size: 2em;
        line-height: 1.25em;
        margin: .25em 0;
    }

    .ft-calendar-h3 {
        font-size: 1.5em;
        line-height: 1em;
        margin: .33em 0;
    }

    .ft-calendar__table {
        border-collapse: collapse;
        width: 100%;
        border-spacing: 0;
    }

    .ft-calendar .container {
        height: 538px;
        left: 50%;
        margin: -255px 0 0 -245px;
        position: absolute;
        top: 50%;
        width: 510px;
    }

    .ft-calendar {
        text-align: center;
    }

    .ft-calendar header {
        position: relative;
    }

    .ft-calendar h2 {
        text-transform: uppercase;
    }

    .ft-calendar thead {
        font-weight: 600;
        text-transform: uppercase;
    }

    .ft-calendar tbody {
        color: #7c8a95;
    }

    .ft-calendar tbody td:hover {
        border: 2px solid #1E7C82;
    }

    .ft-calendar td {
        border: 2px solid transparent;
        border-radius: 50%;
        display: inline-block;
        height: 4em;
        line-height: 4em;
        text-align: center;
        width: 4em;
    }

    .ft-calendar .prev-month,
    .ft-calendar .next-month {
        color: #cbd1d2;
    }

    .ft-calendar .prev-month:hover,
    .ft-calendar .next-month:hover {
        border: 2px solid #cbd1d2;
    }

    .ft-calendar .svg-icon > svg {
        margin-top: 8px;
    }

    .ft-calendar .current-day {
        background: #1E7C82;
        color: #f9f9f9;
    }

    .ft-calendar .event {
        cursor: pointer;
        position: relative;
    }

    .ft-calendar .event:after {
        background: #1E7C82;
        border-radius: 50%;
        bottom: .5em;
        display: block;
        content: '';
        height: .5em;
        left: 50%;
        margin: -.25em 0 0 -.25em;
        position: absolute;
        width: .5em;
    }

    .ft-calendar .event.current-day:after {
        background: #f9f9f9;
    }

    .ft-calendar .btn-prev,
    .ft-calendar .btn-next {
        border: 2px solid #cbd1d2;
        border-radius: 50%;
        color: #cbd1d2;
        height: 40px;
        font-size: .75em;
        line-height: 40px;
        margin: 12px;
        position: absolute;
        top: 50%;
        width: 40px;
    }

    .ft-calendar .btn-prev:hover,
    .ft-calendar .btn-next:hover {
        background: #cbd1d2;
        color: #f9f9f9;
    }

    .ft-calendar .btn-prev {
        left: 6em;
    }

    .ft-calendar .btn-next {
        right: 6em;
    }
</style>
<script>
    import calPanel from './vueEventCalendar.vue'
    export default {
        data: function () {
            return {
                test: 'hola'
            }
        },
        methods: {},
        mounted () {

        },
        computed: {},
        components: {
            'app-event-calender': calPanel
        }
    }
</script>
