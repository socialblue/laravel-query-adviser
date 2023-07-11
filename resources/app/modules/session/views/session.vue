<template>
    <div class="session">
        <router-view name="dialog" />
        <div>
            <page-header name="Queries in session">
                <template #buttons>
                    <button-icon icon="sort" @button:click="sortMenu" title="sort queries" />
                    <button-icon icon="close" @button:click="close" title="clear session cache" />
                </template>
            </page-header>
            <session-row v-bind="data.sessionSummary" :session-key="sessionKey" />

            <div class="tabs">
                <div :class="['tab', {'active': data.listType === 'time'}]" @click="data.listType = 'time'">Time</div>
                <div :class="['tab', {'active': data.listType === 'url'}]" @click="data.listType = 'url'">Routes</div>
                <div :class="['tab', {'active': data.listType === 'referer'}]" @click="data.listType = 'referer'">Referer</div>
                <div :class="['tab', {'active': data.listType === 'rawSql'}]" @click="data.listType = 'rawSql'">Queries</div>
                <div :class="['tab', {'active': data.listType === 'sql'}]" @click="data.listType = 'sql'">Queries with bindings</div>
                <div :class="['tab', {'active': data.listType === 'queryTime'}]" @click="data.listType = 'queryTime'">Query time</div>
            </div>

            <data-grid
                :data-list="dataList"
                :session-key="sessionKey"
                :data-list-key="dataListKey"
                :list-type="data.listType"
            />
        </div>
        <router-view name="sidePanelLeft" :sort-field="data.sortKey" @update:sort-field="setSortField" />
    </div>
</template>

<script setup>
    import {clear, show} from "../api/session-api";
    import DataGrid from "../components/session/datagrid.vue";
    import SessionRow from "../components/sessions/session-row.vue";
    import {onMounted, computed, reactive} from "vue";
    import {RouterView, useRouter} from "vue-router";
    import PageHeader from "../../default/components/page-header.vue";
    import ButtonIcon from "../../default/components/button-icon.vue";

    const router = useRouter();

    const props = defineProps({
        sessionKey: {
            type: String,
            required: true
        },
        time: {
            type: Number,
            default() {
                return 0;
            }
        },
        timeKey: {
            type: Number,
            default() {
                return 0;
            }
        }

    });

    //data
    const data = reactive({
        sortKey: 'time',
        sortDirection: 1,
        listType: 'time',
        sessionData: {},
        sessionSummary: {},
        loading: true,
    });

    function clearQueryCache() {
        clear().finally(() => {
            router.push({name: 'sessions'});
        });
    }

    function setSortField(field) {
        data.sortKey = field;
    }

    function getQueries() {
        data.loading = true;

        // const response = {
        //     "summary": {
        //         "sessionKey": "laravel-query-adviser64a48516f1e698.82143779",
        //         "queries": 6,
        //         "queryTime": 8210.91,
        //         "routes": 5,
        //         "firstQueryLogged": 1688503590,
        //         "lastQueryLogged": 1688503613
        //     },
        //     "data": {
        //         "1688503590": [
        //             {
        //                 "time": 1688503590,
        //                 "timeKey": 0,
        //                 "backtrace": [],
        //                 "sql": "select `label_id`, `label_name`, `traffic_source_campaign`.`url`, `traffic_source_campaign`.`id`, `traffic_source_campaign`.`name`, `traffic_source_campaign`.`status`, `client_campaign`, `country`.`locale` as `country`, `country`.`id` as `country_id`, `client`, `business_name`, `campaign_manager_name`, `account_manager_name`, `marketer`.`name` as `marketer_name`, `marketer`.`id` as `marketer_id`, `client_id`, `client_business_id`, `client_campaign_id`, `client_campaign_status`, `traffic_source`.`name` as `traffic_source_name`, `traffic_source_campaign`.`ad_account_id`, `traffic_source_campaign`.`cbo_budget_type`, `traffic_source_campaign`.`cbo_budget`, `traffic_source_campaign`.`traffic_source_campaign_data`, `traffic_source_campaign`.`revenue_type`, `traffic_source_campaign`.`traffic_source_campaign_id`, `ad_account`.`traffic_source_ad_account_id`, `ad_account`.`timezone`, `ad_account`.`currency` as `costs_currency`, `ad_network_account_business`.`currency`, `ad_network_account`.`traffic_source_network_account_id`, `ad_network_account`.`traffic_source_id`, `client_data`.`has_campaign` as `has_campaign`, COALESCE(days_active, 0) as days_active, COALESCE((ad_click.amount\/ad_view.amount)*100, 0) as ctr, COALESCE(ad_purchase.amount \/ ad_click.amount, 0) as cpc, COALESCE((ad_purchase.amount * 1000)\/ ad_view.amount, 0) as cpm, COALESCE(ad_click.amount, 0) as clicks, @purchase := COALESCE(ad_purchase.amount, 0) as purchase, @leads := COALESCE(ad_revenue.conversion_amount, 0) as leads, @revenue := COALESCE(ad_revenue.amount, 0) as revenue, COALESCE((100 \/ ad_click.amount) * @leads, 0) as cr, COALESCE((@purchase \/ @leads), 0) as cpa, @revenue - @purchase as margin, IF(@revenue = 0 AND @purchase > 0, -100, IF(@revenue > 0 AND @purchase = 0, 100, (100 \/ @revenue) * COALESCE(@revenue - @purchase, 0))) as margin_percentage, (COALESCE(ad_revenue.amount, 0)) as revenue, (COALESCE(ad_revenue.conversion_amount, 0)) as conversions, (COALESCE(ad_revenue.day_amount, 0)) as day_amount, (COALESCE(ad_purchase.amount, 0)) as purchase, (COALESCE(ad_purchase.day_amount, 0)) as day_amount, COALESCE(ad_click.amount, 0) as clicks, COALESCE(ad_view.amount, 0) as views from `traffic_source_campaign` inner join `country` on `country`.`id` = `traffic_source_campaign`.`country_id` inner join `ad_account` on `ad_account`.`id` = `traffic_source_campaign`.`ad_account_id` inner join `ad_network_account` on `ad_network_account`.`id` = `ad_account`.`ad_network_account_id` inner join `business` as `ad_network_account_business` on `ad_network_account_business`.`id` = `ad_network_account`.`business_id` inner join `traffic_source` on `traffic_source`.`id` = `ad_network_account`.`traffic_source_id` inner join `user` as `marketer` on `traffic_source_campaign`.`marketer_id` = `marketer`.`id` left join (select GROUP_CONCAT(client.name) as client, GROUP_CONCAT(campaign.name) as client_campaign, GROUP_CONCAT(business.name) as business_name, GROUP_CONCAT(campaign_manager.name) as campaign_manager_name, GROUP_CONCAT(account_manager.name) as account_manager_name, `path_traffic_source_campaign`.`traffic_source_campaign_id` as `id`, `client`.`id` as `client_id`, `label`.`id` as `label_id`, `label`.`name` as `label_name`, `account_manager`.`id` as `account_manager_id`, `campaign_manager`.`id` as `campaign_manager_id`, `client`.`business_id` as `client_business_id`, `campaign_group`.`country_id` as `campaign_group_country_id`, `campaign`.`id` as `client_campaign_id`, `campaign`.`status` as `client_campaign_status`, campaign_path.campaign_id IS NOT NULL as has_campaign from `path_traffic_source_campaign` inner join `path` on `path`.`id` = `path_traffic_source_campaign`.`path_id` inner join `campaign_path` on `campaign_path`.`path_id` = `path`.`id` inner join `campaign` on `campaign_path`.`campaign_id` = `campaign`.`id` inner join `campaign_group` on `campaign`.`campaign_group_id` = `campaign_group`.`id` inner join `client` on `campaign_group`.`client_id` = `client`.`id` left join `user` as `campaign_manager` on `client`.`campaign_manager_id` = `campaign_manager`.`id` inner join `user` as `account_manager` on `client`.`account_manager_id` = `account_manager`.`id` left join `label` on `label`.`id` = `campaign`.`label_id` inner join `business` on `business`.`id` = `client`.`business_id` left join `campaign_group_contract` as `cc` on `cc`.`campaign_group_id` = `campaign_group`.`id` and COALESCE(cc.end_date,  DATE(CONVERT_TZ(NOW(), \"Europe\/Amsterdam\", COALESCE(campaign_group.timezone, \"Europe\/Amsterdam\")))) >= DATE(CONVERT_TZ(NOW(), \"Europe\/Amsterdam\", COALESCE(campaign_group.timezone, \"Europe\/Amsterdam\"))) and `cc`.`start_date` <= DATE(CONVERT_TZ(NOW(), \"Europe\/Amsterdam\", COALESCE(campaign_group.timezone, \"Europe\/Amsterdam\"))) group by `path_traffic_source_campaign`.`traffic_source_campaign_id`) as `client_data` on `client_data`.`id` = `traffic_source_campaign`.`id` left join (select SUM(ad_revenue.amount \/ COALESCE(currency_exchange_rate.rate, fallback_exchange_rate.rate, 1)) as amount, SUM(IF(ad_revenue.date = \"2023-07-04\", ad_revenue.amount \/ COALESCE(currency_exchange_rate.rate, fallback_exchange_rate.rate, 1), 0)) as day_amount, `traffic_source_campaign`.`id`, `ad_revenue`.`date`, SUM(ad_revenue.conversion_amount) as conversion_amount from `ad_revenue` inner join `ad` on `ad`.`id` = `ad_revenue`.`ad_id` inner join `ad_set` on `ad_set`.`id` = `ad`.`ad_set_id` inner join `traffic_source_campaign` on `traffic_source_campaign`.`id` = `ad_set`.`traffic_source_campaign_id` inner join `ad_account` on `ad_account`.`id` = `traffic_source_campaign`.`ad_account_id` inner join `ad_network_account` on `ad_network_account`.`id` = `ad_account`.`ad_network_account_id` inner join `traffic_source` on `traffic_source`.`id` = `ad_network_account`.`traffic_source_id` inner join `business` on `business`.`id` = `ad_network_account`.`business_id` left join `currency_exchange_rate` on `currency_exchange_rate`.`from_currency` = \"EUR\" and `currency_exchange_rate`.`to_currency` = `business`.`currency` and `currency_exchange_rate`.`date` = \"2023-07-04\" left join `currency_exchange_rate` as `fallback_exchange_rate` on `fallback_exchange_rate`.`to_currency` = `business`.`currency` and `fallback_exchange_rate`.`from_currency` = \"EUR\" and `fallback_exchange_rate`.`date` = '2023-07-03' where `ad_revenue`.`date` between '2023-05-01' and '2023-06-01' and `traffic_source`.`traffic_type` in ('10') group by `traffic_source_campaign`.`id`) as `ad_revenue` on `ad_revenue`.`id` = `traffic_source_campaign`.`id` left join (select SUM(ad_purchase.amount \/ COALESCE(currency_exchange_rate.rate, fallback_exchange_rate.rate, 1)) as amount, SUM(IF(ad_purchase.date = \"2023-07-04\", ad_purchase.amount \/ COALESCE(currency_exchange_rate.rate, fallback_exchange_rate.rate, 1), 0)) as day_amount, `traffic_source_campaign`.`id`, `ad_purchase`.`date`, (COALESCE(COUNT(DISTINCT ad_purchase.date))) as days_active from `ad_purchase` inner join `ad` on `ad`.`id` = `ad_purchase`.`ad_id` inner join `ad_set` on `ad_set`.`id` = `ad`.`ad_set_id` inner join `traffic_source_campaign` on `traffic_source_campaign`.`id` = `ad_set`.`traffic_source_campaign_id` inner join `ad_account` on `ad_account`.`id` = `traffic_source_campaign`.`ad_account_id` inner join `ad_network_account` on `ad_network_account`.`id` = `ad_account`.`ad_network_account_id` inner join `traffic_source` on `traffic_source`.`id` = `ad_network_account`.`traffic_source_id` left join `currency_exchange_rate` on `currency_exchange_rate`.`from_currency` = \"EUR\" and `currency_exchange_rate`.`to_currency` = `ad_account`.`currency` and `currency_exchange_rate`.`date` = \"2023-07-04\" left join `currency_exchange_rate` as `fallback_exchange_rate` on `fallback_exchange_rate`.`to_currency` = `ad_account`.`currency` and `fallback_exchange_rate`.`from_currency` = \"EUR\" and `fallback_exchange_rate`.`date` = '2023-07-03' where `ad_purchase`.`date` between '2023-05-01' and '2023-06-01' and `traffic_source`.`traffic_type` in ('10') group by `traffic_source_campaign`.`id`) as `ad_purchase` on `ad_purchase`.`id` = `traffic_source_campaign`.`id` left join (select SUM(amount) as amount, `ad_click`.`date`, `traffic_source_campaign`.`id` from `ad_click` inner join `ad` on `ad`.`id` = `ad_click`.`ad_id` inner join `ad_set` on `ad_set`.`id` = `ad`.`ad_set_id` inner join `traffic_source_campaign` on `traffic_source_campaign`.`id` = `ad_set`.`traffic_source_campaign_id` inner join `ad_account` on `ad_account`.`id` = `traffic_source_campaign`.`ad_account_id` inner join `ad_network_account` on `ad_network_account`.`id` = `ad_account`.`ad_network_account_id` inner join `traffic_source` on `traffic_source`.`id` = `ad_network_account`.`traffic_source_id` where `ad_click`.`date` between '2023-05-01' and '2023-06-01' and `traffic_source`.`traffic_type` in ('10') group by `traffic_source_campaign`.`id`) as `ad_click` on `ad_click`.`id` = `traffic_source_campaign`.`id` left join (select SUM(amount) as amount, `ad_view`.`date`, `traffic_source_campaign`.`id` from `ad_view` inner join `ad` on `ad`.`id` = `ad_view`.`ad_id` inner join `ad_set` on `ad_set`.`id` = `ad`.`ad_set_id` inner join `traffic_source_campaign` on `traffic_source_campaign`.`id` = `ad_set`.`traffic_source_campaign_id` inner join `ad_account` on `ad_account`.`id` = `traffic_source_campaign`.`ad_account_id` inner join `ad_network_account` on `ad_network_account`.`id` = `ad_account`.`ad_network_account_id` inner join `traffic_source` on `traffic_source`.`id` = `ad_network_account`.`traffic_source_id` where `ad_view`.`date` between '2023-05-01' and '2023-06-01' and `traffic_source`.`traffic_type` in ('10') group by `traffic_source_campaign`.`id`) as `ad_view` on `ad_view`.`id` = `traffic_source_campaign`.`id` inner join (select `ad`.`id` as `ad_id`, `traffic_source_campaign`.`id` as `id` from `traffic_source` inner join `ad_network_account` on `traffic_source`.`id` = `ad_network_account`.`traffic_source_id` inner join `ad_account` on `ad_network_account`.`id` = `ad_account`.`ad_network_account_id` inner join `traffic_source_campaign` on `ad_account`.`id` = `traffic_source_campaign`.`ad_account_id` left join `ad_set` on `traffic_source_campaign`.`id` = `ad_set`.`traffic_source_campaign_id` left join `ad` on `ad_set`.`id` = `ad`.`ad_set_id` where `traffic_source`.`traffic_type` in ('10') and `traffic_source`.`deleted_at` is null group by `traffic_source_campaign`.`id`) as `filters` on `filters`.`id` = `traffic_source_campaign`.`id` where (ad_revenue.amount IS NOT NULL OR ad_purchase.amount IS NOT NULL) and `traffic_source_campaign`.`deleted_at` is null group by `traffic_source_campaign`.`id` limit 100000 offset 0",
        //                 "rawSql": "select `label_id`, `label_name`, `traffic_source_campaign`.`url`, `traffic_source_campaign`.`id`, `traffic_source_campaign`.`name`, `traffic_source_campaign`.`status`, `client_campaign`, `country`.`locale` as `country`, `country`.`id` as `country_id`, `client`, `business_name`, `campaign_manager_name`, `account_manager_name`, `marketer`.`name` as `marketer_name`, `marketer`.`id` as `marketer_id`, `client_id`, `client_business_id`, `client_campaign_id`, `client_campaign_status`, `traffic_source`.`name` as `traffic_source_name`, `traffic_source_campaign`.`ad_account_id`, `traffic_source_campaign`.`cbo_budget_type`, `traffic_source_campaign`.`cbo_budget`, `traffic_source_campaign`.`traffic_source_campaign_data`, `traffic_source_campaign`.`revenue_type`, `traffic_source_campaign`.`traffic_source_campaign_id`, `ad_account`.`traffic_source_ad_account_id`, `ad_account`.`timezone`, `ad_account`.`currency` as `costs_currency`, `ad_network_account_business`.`currency`, `ad_network_account`.`traffic_source_network_account_id`, `ad_network_account`.`traffic_source_id`, `client_data`.`has_campaign` as `has_campaign`, COALESCE(days_active, 0) as days_active, COALESCE((ad_click.amount\/ad_view.amount)*100, 0) as ctr, COALESCE(ad_purchase.amount \/ ad_click.amount, 0) as cpc, COALESCE((ad_purchase.amount * 1000)\/ ad_view.amount, 0) as cpm, COALESCE(ad_click.amount, 0) as clicks, @purchase := COALESCE(ad_purchase.amount, 0) as purchase, @leads := COALESCE(ad_revenue.conversion_amount, 0) as leads, @revenue := COALESCE(ad_revenue.amount, 0) as revenue, COALESCE((100 \/ ad_click.amount) * @leads, 0) as cr, COALESCE((@purchase \/ @leads), 0) as cpa, @revenue - @purchase as margin, IF(@revenue = 0 AND @purchase > 0, -100, IF(@revenue > 0 AND @purchase = 0, 100, (100 \/ @revenue) * COALESCE(@revenue - @purchase, 0))) as margin_percentage, (COALESCE(ad_revenue.amount, 0)) as revenue, (COALESCE(ad_revenue.conversion_amount, 0)) as conversions, (COALESCE(ad_revenue.day_amount, 0)) as day_amount, (COALESCE(ad_purchase.amount, 0)) as purchase, (COALESCE(ad_purchase.day_amount, 0)) as day_amount, COALESCE(ad_click.amount, 0) as clicks, COALESCE(ad_view.amount, 0) as views from `traffic_source_campaign` inner join `country` on `country`.`id` = `traffic_source_campaign`.`country_id` inner join `ad_account` on `ad_account`.`id` = `traffic_source_campaign`.`ad_account_id` inner join `ad_network_account` on `ad_network_account`.`id` = `ad_account`.`ad_network_account_id` inner join `business` as `ad_network_account_business` on `ad_network_account_business`.`id` = `ad_network_account`.`business_id` inner join `traffic_source` on `traffic_source`.`id` = `ad_network_account`.`traffic_source_id` inner join `user` as `marketer` on `traffic_source_campaign`.`marketer_id` = `marketer`.`id` left join (select GROUP_CONCAT(client.name) as client, GROUP_CONCAT(campaign.name) as client_campaign, GROUP_CONCAT(business.name) as business_name, GROUP_CONCAT(campaign_manager.name) as campaign_manager_name, GROUP_CONCAT(account_manager.name) as account_manager_name, `path_traffic_source_campaign`.`traffic_source_campaign_id` as `id`, `client`.`id` as `client_id`, `label`.`id` as `label_id`, `label`.`name` as `label_name`, `account_manager`.`id` as `account_manager_id`, `campaign_manager`.`id` as `campaign_manager_id`, `client`.`business_id` as `client_business_id`, `campaign_group`.`country_id` as `campaign_group_country_id`, `campaign`.`id` as `client_campaign_id`, `campaign`.`status` as `client_campaign_status`, campaign_path.campaign_id IS NOT NULL as has_campaign from `path_traffic_source_campaign` inner join `path` on `path`.`id` = `path_traffic_source_campaign`.`path_id` inner join `campaign_path` on `campaign_path`.`path_id` = `path`.`id` inner join `campaign` on `campaign_path`.`campaign_id` = `campaign`.`id` inner join `campaign_group` on `campaign`.`campaign_group_id` = `campaign_group`.`id` inner join `client` on `campaign_group`.`client_id` = `client`.`id` left join `user` as `campaign_manager` on `client`.`campaign_manager_id` = `campaign_manager`.`id` inner join `user` as `account_manager` on `client`.`account_manager_id` = `account_manager`.`id` left join `label` on `label`.`id` = `campaign`.`label_id` inner join `business` on `business`.`id` = `client`.`business_id` left join `campaign_group_contract` as `cc` on `cc`.`campaign_group_id` = `campaign_group`.`id` and COALESCE(cc.end_date,  DATE(CONVERT_TZ(NOW(), \"Europe\/Amsterdam\", COALESCE(campaign_group.timezone, \"Europe\/Amsterdam\")))) >= DATE(CONVERT_TZ(NOW(), \"Europe\/Amsterdam\", COALESCE(campaign_group.timezone, \"Europe\/Amsterdam\"))) and `cc`.`start_date` <= DATE(CONVERT_TZ(NOW(), \"Europe\/Amsterdam\", COALESCE(campaign_group.timezone, \"Europe\/Amsterdam\"))) group by `path_traffic_source_campaign`.`traffic_source_campaign_id`) as `client_data` on `client_data`.`id` = `traffic_source_campaign`.`id` left join (select SUM(ad_revenue.amount \/ COALESCE(currency_exchange_rate.rate, fallback_exchange_rate.rate, 1)) as amount, SUM(IF(ad_revenue.date = \"2023-07-04\", ad_revenue.amount \/ COALESCE(currency_exchange_rate.rate, fallback_exchange_rate.rate, 1), 0)) as day_amount, `traffic_source_campaign`.`id`, `ad_revenue`.`date`, SUM(ad_revenue.conversion_amount) as conversion_amount from `ad_revenue` inner join `ad` on `ad`.`id` = `ad_revenue`.`ad_id` inner join `ad_set` on `ad_set`.`id` = `ad`.`ad_set_id` inner join `traffic_source_campaign` on `traffic_source_campaign`.`id` = `ad_set`.`traffic_source_campaign_id` inner join `ad_account` on `ad_account`.`id` = `traffic_source_campaign`.`ad_account_id` inner join `ad_network_account` on `ad_network_account`.`id` = `ad_account`.`ad_network_account_id` inner join `traffic_source` on `traffic_source`.`id` = `ad_network_account`.`traffic_source_id` inner join `business` on `business`.`id` = `ad_network_account`.`business_id` left join `currency_exchange_rate` on `currency_exchange_rate`.`from_currency` = \"EUR\" and `currency_exchange_rate`.`to_currency` = `business`.`currency` and `currency_exchange_rate`.`date` = \"2023-07-04\" left join `currency_exchange_rate` as `fallback_exchange_rate` on `fallback_exchange_rate`.`to_currency` = `business`.`currency` and `fallback_exchange_rate`.`from_currency` = \"EUR\" and `fallback_exchange_rate`.`date` = '2023-07-03' where `ad_revenue`.`date` between ? and ? and `traffic_source`.`traffic_type` in (?) group by `traffic_source_campaign`.`id`) as `ad_revenue` on `ad_revenue`.`id` = `traffic_source_campaign`.`id` left join (select SUM(ad_purchase.amount \/ COALESCE(currency_exchange_rate.rate, fallback_exchange_rate.rate, 1)) as amount, SUM(IF(ad_purchase.date = \"2023-07-04\", ad_purchase.amount \/ COALESCE(currency_exchange_rate.rate, fallback_exchange_rate.rate, 1), 0)) as day_amount, `traffic_source_campaign`.`id`, `ad_purchase`.`date`, (COALESCE(COUNT(DISTINCT ad_purchase.date))) as days_active from `ad_purchase` inner join `ad` on `ad`.`id` = `ad_purchase`.`ad_id` inner join `ad_set` on `ad_set`.`id` = `ad`.`ad_set_id` inner join `traffic_source_campaign` on `traffic_source_campaign`.`id` = `ad_set`.`traffic_source_campaign_id` inner join `ad_account` on `ad_account`.`id` = `traffic_source_campaign`.`ad_account_id` inner join `ad_network_account` on `ad_network_account`.`id` = `ad_account`.`ad_network_account_id` inner join `traffic_source` on `traffic_source`.`id` = `ad_network_account`.`traffic_source_id` left join `currency_exchange_rate` on `currency_exchange_rate`.`from_currency` = \"EUR\" and `currency_exchange_rate`.`to_currency` = `ad_account`.`currency` and `currency_exchange_rate`.`date` = \"2023-07-04\" left join `currency_exchange_rate` as `fallback_exchange_rate` on `fallback_exchange_rate`.`to_currency` = `ad_account`.`currency` and `fallback_exchange_rate`.`from_currency` = \"EUR\" and `fallback_exchange_rate`.`date` = '2023-07-03' where `ad_purchase`.`date` between ? and ? and `traffic_source`.`traffic_type` in (?) group by `traffic_source_campaign`.`id`) as `ad_purchase` on `ad_purchase`.`id` = `traffic_source_campaign`.`id` left join (select SUM(amount) as amount, `ad_click`.`date`, `traffic_source_campaign`.`id` from `ad_click` inner join `ad` on `ad`.`id` = `ad_click`.`ad_id` inner join `ad_set` on `ad_set`.`id` = `ad`.`ad_set_id` inner join `traffic_source_campaign` on `traffic_source_campaign`.`id` = `ad_set`.`traffic_source_campaign_id` inner join `ad_account` on `ad_account`.`id` = `traffic_source_campaign`.`ad_account_id` inner join `ad_network_account` on `ad_network_account`.`id` = `ad_account`.`ad_network_account_id` inner join `traffic_source` on `traffic_source`.`id` = `ad_network_account`.`traffic_source_id` where `ad_click`.`date` between ? and ? and `traffic_source`.`traffic_type` in (?) group by `traffic_source_campaign`.`id`) as `ad_click` on `ad_click`.`id` = `traffic_source_campaign`.`id` left join (select SUM(amount) as amount, `ad_view`.`date`, `traffic_source_campaign`.`id` from `ad_view` inner join `ad` on `ad`.`id` = `ad_view`.`ad_id` inner join `ad_set` on `ad_set`.`id` = `ad`.`ad_set_id` inner join `traffic_source_campaign` on `traffic_source_campaign`.`id` = `ad_set`.`traffic_source_campaign_id` inner join `ad_account` on `ad_account`.`id` = `traffic_source_campaign`.`ad_account_id` inner join `ad_network_account` on `ad_network_account`.`id` = `ad_account`.`ad_network_account_id` inner join `traffic_source` on `traffic_source`.`id` = `ad_network_account`.`traffic_source_id` where `ad_view`.`date` between ? and ? and `traffic_source`.`traffic_type` in (?) group by `traffic_source_campaign`.`id`) as `ad_view` on `ad_view`.`id` = `traffic_source_campaign`.`id` inner join (select `ad`.`id` as `ad_id`, `traffic_source_campaign`.`id` as `id` from `traffic_source` inner join `ad_network_account` on `traffic_source`.`id` = `ad_network_account`.`traffic_source_id` inner join `ad_account` on `ad_network_account`.`id` = `ad_account`.`ad_network_account_id` inner join `traffic_source_campaign` on `ad_account`.`id` = `traffic_source_campaign`.`ad_account_id` left join `ad_set` on `traffic_source_campaign`.`id` = `ad_set`.`traffic_source_campaign_id` left join `ad` on `ad_set`.`id` = `ad`.`ad_set_id` where `traffic_source`.`traffic_type` in (?) and `traffic_source`.`deleted_at` is null group by `traffic_source_campaign`.`id`) as `filters` on `filters`.`id` = `traffic_source_campaign`.`id` where (ad_revenue.amount IS NOT NULL OR ad_purchase.amount IS NOT NULL) and `traffic_source_campaign`.`deleted_at` is null group by `traffic_source_campaign`.`id` limit 100000 offset 0",
        //                 "bindings": [
        //                     "2023-05-01",
        //                     "2023-06-01",
        //                     "10",
        //                     "2023-05-01",
        //                     "2023-06-01",
        //                     "10",
        //                     "2023-05-01",
        //                     "2023-06-01",
        //                     "10",
        //                     "2023-05-01",
        //                     "2023-06-01",
        //                     "10",
        //                     "10"
        //                 ],
        //                 "queryTime": 8081.21,
        //                 "url": "http:\/\/bert.test\/api\/insights\/summarized\/traffic-source-campaign",
        //                 "referer": "http:\/\/bert.test\/admin\/campaign\/social"
        //             }
        //         ],
        //         "1688503597": [
        //             {
        //                 "time": 1688503597,
        //                 "timeKey": 0,
        //                 "backtrace": [
        //                     {
        //                         "file": "\/var\/www\/vendor\/socialblue\/sam-client\/src\/SamServiceProvider.php",
        //                         "line": 86,
        //                         "function": "all",
        //                         "class": "Illuminate\\Database\\Eloquent\\Builder",
        //                         "type": "->",
        //                         "model": "SocialBlue\\Sam\\Models\\SamPublisher"
        //                     }
        //                 ],
        //                 "sql": "select * from `sam_publisher`",
        //                 "rawSql": "select * from `sam_publisher`",
        //                 "bindings": [],
        //                 "queryTime": 5.59,
        //                 "url": "http:\/\/bert.test\/api\/tools\/data-list\/business",
        //                 "referer": "http:\/\/bert.test\/admin\/campaign\/social?search=eyJtYW5hZ2VyIjpbXSwiYnVzaW5lc3MiOltdLCJjb3VudHJ5IjpbXSwiY2xpZW50IjpbXSwiY2FtcGFpZ24iOltdLCJsYWJlbCI6W10sImRhdGVzIjp7InN0YXJ0IjoiMjAyMy0wNS0wMSIsImVuZCI6IjIwMjMtMDYtMDEifSwicmV2ZW51ZV90eXBlIjpbXSwidHJhZmZpY19zb3VyY2VfdHJhZmZpY190eXBlIjpbMTBdLCJ0cmFmZmljX3NvdXJjZV9jYW1wYWlnbl9pZCI6W10sInN0YXR1cyI6InJldmVudWUiLCJzZWFyY2giOiIifQ%3D%3D"
        //             },
        //             {
        //                 "time": 1688503597,
        //                 "timeKey": 1,
        //                 "backtrace": [],
        //                 "sql": "select `id`, `name` from `business` where `business`.`deleted_at` is null order by `name` asc limit 50 offset 0",
        //                 "rawSql": "select `id`, `name` from `business` where `business`.`deleted_at` is null order by `name` asc limit 50 offset 0",
        //                 "bindings": [],
        //                 "queryTime": 0.69,
        //                 "url": "http:\/\/bert.test\/api\/tools\/data-list\/business",
        //                 "referer": "http:\/\/bert.test\/admin\/campaign\/social?search=eyJtYW5hZ2VyIjpbXSwiYnVzaW5lc3MiOltdLCJjb3VudHJ5IjpbXSwiY2xpZW50IjpbXSwiY2FtcGFpZ24iOltdLCJsYWJlbCI6W10sImRhdGVzIjp7InN0YXJ0IjoiMjAyMy0wNS0wMSIsImVuZCI6IjIwMjMtMDYtMDEifSwicmV2ZW51ZV90eXBlIjpbXSwidHJhZmZpY19zb3VyY2VfdHJhZmZpY190eXBlIjpbMTBdLCJ0cmFmZmljX3NvdXJjZV9jYW1wYWlnbl9pZCI6W10sInN0YXR1cyI6InJldmVudWUiLCJzZWFyY2giOiIifQ%3D%3D"
        //             }
        //         ],
        //         "1688503602": [
        //             {
        //                 "time": 1688503602,
        //                 "timeKey": 0,
        //                 "backtrace": [],
        //                 "sql": "select `id`, `name` from `country` where `country`.`deleted_at` is null order by `name` asc limit 50 offset 0",
        //                 "rawSql": "select `id`, `name` from `country` where `country`.`deleted_at` is null order by `name` asc limit 50 offset 0",
        //                 "bindings": [],
        //                 "queryTime": 3.63,
        //                 "url": "http:\/\/bert.test\/api\/tools\/data-list\/country",
        //                 "referer": "http:\/\/bert.test\/admin\/campaign\/social?search=eyJtYW5hZ2VyIjpbXSwiYnVzaW5lc3MiOltdLCJjb3VudHJ5IjpbXSwiY2xpZW50IjpbXSwiY2FtcGFpZ24iOltdLCJsYWJlbCI6W10sImRhdGVzIjp7InN0YXJ0IjoiMjAyMy0wNS0wMSIsImVuZCI6IjIwMjMtMDYtMDEifSwicmV2ZW51ZV90eXBlIjpbXSwidHJhZmZpY19zb3VyY2VfdHJhZmZpY190eXBlIjpbMTBdLCJ0cmFmZmljX3NvdXJjZV9jYW1wYWlnbl9pZCI6W10sInN0YXR1cyI6InJldmVudWUiLCJzZWFyY2giOiIifQ%3D%3D"
        //             }
        //         ],
        //         "1688503609": [
        //             {
        //                 "time": 1688503609,
        //                 "timeKey": 0,
        //                 "backtrace": [],
        //                 "sql": "select `campaign`.`id`, `campaign`.`name` from `campaign` inner join `campaign_group` on `campaign_group`.`id` = `campaign`.`campaign_group_id` where `campaign_group`.`traffic_type` in ('10') and `campaign`.`deleted_at` is null order by `campaign`.`name` asc limit 50 offset 0",
        //                 "rawSql": "select `campaign`.`id`, `campaign`.`name` from `campaign` inner join `campaign_group` on `campaign_group`.`id` = `campaign`.`campaign_group_id` where `campaign_group`.`traffic_type` in (?) and `campaign`.`deleted_at` is null order by `campaign`.`name` asc limit 50 offset 0",
        //                 "bindings": [
        //                     "10"
        //                 ],
        //                 "queryTime": 21.8,
        //                 "url": "http:\/\/bert.test\/api\/tools\/data-list\/campaign",
        //                 "referer": "http:\/\/bert.test\/admin\/campaign\/social?search=eyJtYW5hZ2VyIjpbXSwiYnVzaW5lc3MiOltdLCJjb3VudHJ5IjpbXSwiY2xpZW50IjpbXSwiY2FtcGFpZ24iOltdLCJsYWJlbCI6W10sImRhdGVzIjp7InN0YXJ0IjoiMjAyMy0wNS0wMSIsImVuZCI6IjIwMjMtMDYtMDEifSwicmV2ZW51ZV90eXBlIjpbXSwidHJhZmZpY19zb3VyY2VfdHJhZmZpY190eXBlIjpbMTBdLCJ0cmFmZmljX3NvdXJjZV9jYW1wYWlnbl9pZCI6W10sInN0YXR1cyI6InJldmVudWUiLCJzZWFyY2giOiIifQ%3D%3D"
        //             }
        //         ],
        //         "1688503613": [
        //             {
        //                 "time": 1688503613,
        //                 "timeKey": 0,
        //                 "backtrace": [],
        //                 "sql": "select `traffic_source_campaign`.`id`, `traffic_source_campaign`.`name`, `traffic_source_campaign`.`ad_account_id`, `traffic_source_campaign`.`cbo_budget`, `ad_network_account`.`traffic_source_id` as `traffic_source_id`, `traffic_source`.`name` as `traffic_source_name`, `traffic_source_campaign`.`revenue_type`, `traffic_source_campaign`.`traffic_source_campaign_id` as `traffic_source_campaign_id_from_traffic_source` from `traffic_source_campaign` inner join `path_traffic_source_campaign` on `path_traffic_source_campaign`.`traffic_source_campaign_id` = `traffic_source_campaign`.`id` inner join `path` on `path_traffic_source_campaign`.`path_id` = `path`.`id` inner join `campaign_path` on `campaign_path`.`path_id` = `path`.`id` inner join `campaign` on `campaign_path`.`campaign_id` = `campaign`.`id` inner join `ad_account` on `ad_account`.`id` = `traffic_source_campaign`.`ad_account_id` inner join `ad_network_account` on `ad_network_account`.`id` = `ad_account`.`ad_network_account_id` inner join `traffic_source` on `traffic_source`.`id` = `ad_network_account`.`traffic_source_id` inner join `campaign_group` on `campaign_group`.`id` = `campaign`.`campaign_group_id` inner join `client` on `client`.`id` = `campaign_group`.`client_id` where `traffic_source`.`traffic_type` in ('10') and `traffic_source_campaign`.`deleted_at` is null order by `traffic_source_campaign`.`name` asc limit 50 offset 0",
        //                 "rawSql": "select `traffic_source_campaign`.`id`, `traffic_source_campaign`.`name`, `traffic_source_campaign`.`ad_account_id`, `traffic_source_campaign`.`cbo_budget`, `ad_network_account`.`traffic_source_id` as `traffic_source_id`, `traffic_source`.`name` as `traffic_source_name`, `traffic_source_campaign`.`revenue_type`, `traffic_source_campaign`.`traffic_source_campaign_id` as `traffic_source_campaign_id_from_traffic_source` from `traffic_source_campaign` inner join `path_traffic_source_campaign` on `path_traffic_source_campaign`.`traffic_source_campaign_id` = `traffic_source_campaign`.`id` inner join `path` on `path_traffic_source_campaign`.`path_id` = `path`.`id` inner join `campaign_path` on `campaign_path`.`path_id` = `path`.`id` inner join `campaign` on `campaign_path`.`campaign_id` = `campaign`.`id` inner join `ad_account` on `ad_account`.`id` = `traffic_source_campaign`.`ad_account_id` inner join `ad_network_account` on `ad_network_account`.`id` = `ad_account`.`ad_network_account_id` inner join `traffic_source` on `traffic_source`.`id` = `ad_network_account`.`traffic_source_id` inner join `campaign_group` on `campaign_group`.`id` = `campaign`.`campaign_group_id` inner join `client` on `client`.`id` = `campaign_group`.`client_id` where `traffic_source`.`traffic_type` in (?) and `traffic_source_campaign`.`deleted_at` is null order by `traffic_source_campaign`.`name` asc limit 50 offset 0",
        //                 "bindings": [
        //                     "10"
        //                 ],
        //                 "queryTime": 97.99,
        //                 "url": "http:\/\/bert.test\/api\/tools\/data-list\/traffic-source-campaign",
        //                 "referer": "http:\/\/bert.test\/admin\/campaign\/social?search=eyJtYW5hZ2VyIjpbXSwiYnVzaW5lc3MiOltdLCJjb3VudHJ5IjpbXSwiY2xpZW50IjpbXSwiY2FtcGFpZ24iOltdLCJsYWJlbCI6W10sImRhdGVzIjp7InN0YXJ0IjoiMjAyMy0wNS0wMSIsImVuZCI6IjIwMjMtMDYtMDEifSwicmV2ZW51ZV90eXBlIjpbXSwidHJhZmZpY19zb3VyY2VfdHJhZmZpY190eXBlIjpbMTBdLCJ0cmFmZmljX3NvdXJjZV9jYW1wYWlnbl9pZCI6W10sInN0YXR1cyI6InJldmVudWUiLCJzZWFyY2giOiIifQ%3D%3D"
        //             }
        //         ]
        //     }
        // };

        show(props.sessionKey).then((cachedKeys) => {
            data.sessionData = cachedKeys['data'] ?? [];
            data.sessionSummary = cachedKeys['summary'] ?? {};
        }).finally(() => {
            data.loading = false;
        });
    }

    function showQueryGroup(time) {
        return data.showTime.includes(time);
    }

    function toggleQueryGroup(time) {
        if (data.showTime.includes(time)) {
            data.showTime = data.showTime.filter(val => val !== time);
            return;
        }
        data.showTime.push(time);
    }

    function groupTitle(value) {
        if (data.listType === "time") {
            return new Date(value * 1000).toISOString();
        }
        return value;
    }

    function getUniqueValuesByKey(key) {
        return [...new Set(flattenedCachedKeys.value.map(val => val[key]))];
    }

    function groupValuesByKey(key) {

        let sortClosure = (a, b) => {
            if (a[data.sortKey] === b[data.sortKey]) {
                return 0;
            } else if(a[data.sortKey] > b[data.sortKey]) {
                return -1 * data.sortDirection
            }
            return data.sortDirection;
        };

        if (data.sortKey === 'amount') {
            sortClosure = () => {
                return 0;
            }
        }

        let cdata = {};
        getUniqueValuesByKey(key).forEach((uniqueValue) => {
            cdata[uniqueValue] = flattenedCachedKeys.value
                .filter(row => row[key] === uniqueValue)
                .sort(
                    sortClosure
                );
        });

        return cdata;
    }

    function showFilterMenu() {
        router.push({name: 'session-order-menu'})
    }

    function close() {
        router.push({
            name: 'sessions'
        });
    }

    const dataList = computed(() => {
        return groupValuesByKey(data.listType);
    })

    const dataListKey = computed(() => {
        let list = dataList.value;

        let sortClosure = (a, b) => {
            if (list[a][0][data.sortKey] === list[b][0][data.sortKey]) {
                return 0;
            } else if(list[a][0][data.sortKey] > list[b][0][data.sortKey]) {
                return -1 * data.sortDirection
            }
            return data.sortDirection;
        };

        if (data.sortKey === 'amount') {
            sortClosure = (a, b) => {
                if (list[a].length === list[b].length) {
                    return 0;
                } else if(list[a].length > list[b].length) {
                    return -1 * data.sortDirection;
                }
                return data.sortDirection;
            }
        }

        return Object.keys(list).sort(sortClosure);
    });

    const flattenedCachedKeys = computed(() => {
        return Object.values(data.sessionData).flat();
    });

    const totalQueryTime = computed(() => {
        if (flattenedCachedKeys.value.length === 0) {
            return 0;
        }
        return flattenedCachedKeys.value.reduce((total, time, index) => {
            if (index === 1) {
                total = total.queryTime;
            }
            return total + time.queryTime;
        });
    })

    const totalAmountOfQueries = computed(() => {
        flattenedCachedKeys.value.length;
    });

    const amountOfRoutes = computed( () => {
        return getUniqueRoutes.value.length;
    });

    const getUniqueRoutes = computed( () => {
        return getUniqueValuesByKey('url');
    })

    const getUniqueRawSql = computed( () => {
        return getUniqueValuesByKey('rawSql');
    })

    const getRawQueryList = computed( () => {
        return groupValuesByKey('rawSql');
    });

    const getRouteQueryList= computed( () => {
        return groupValuesByKey('url');
    });

    function sortMenu()
    {
        router.push({
            name: 'session-order-menu'
        });
    }


    onMounted(() => {
        getQueries();
    })


</script>

<style lang="scss">
    $header-height: 44px;
    $metrics-height: 68px;
    $footer-height: 56px;
    $tabs-height: 42px;
    $tabs-size-diff: 42px - 33px;

    $data-grid-height-minus: ($header-height + $footer-height + $metrics-height + $tabs-height);

    @media (max-height: 480px) {
        .session {
            .tabs {
                font-size: 10px;
            }

            .datagrid {
                max-height: calc(100vh - ($data-grid-height-minus -  $metrics-height - $tabs-size-diff));
            }

            .metrics {
                display: none;
            }
        }

    }

    @media (max-height: 280px) {
        .session {
            .datagrid {
                max-height: calc(100vh - ($data-grid-height-minus -  $metrics-height - $footer-height -  $tabs-size-diff));
            }

            .tabs {
                font-size: 10px;
            }
        }

        footer {
            display: none;
        }
    }


    @media only screen and (max-width: 640px) {
        .session {
            .tabs {
                font-size: 10px;
            }

            .metrics {
                .buttons {
                    display: none;
                    .button {
                        display: none;
                    }
                }
            }
        }
    }


    .tabs {
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        border-bottom: 1px solid rgba(233,233,233,.9);

        .tab {
            padding: 0.5rem 1rem;
            border-bottom: none;
            cursor: pointer;
            background: #fff;
            margin-right: 0.5rem;
            color: #000;
            font-weight: 900;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
            border-bottom: 2px solid transparent;

            &:hover {
                background: #eee;
                border-bottom: 2px solid rgba(33,180,180, .5);
            }

            &.active {
                border-bottom: 2px solid rgba(33,180,180,.9);
            }
        }

    }

</style>
