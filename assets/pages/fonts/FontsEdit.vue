<template>


</template>

<script setup>
import { onBeforeMount } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useApi } from '../../library/api';
import { useNotifier } from '../../library/notifier';
import { useBusy } from '../../stores/busy';

const route = useRoute();
const router = useRouter();
const api = useApi();
const busy = useBusy();
const notifier = useNotifier();

onBeforeMount(async () => {
    console.log(route.params);

    busy.add('fonts.edit:fetch-item');
    let promise = api
        .request({
            method: 'GET',
            url: `/fonts/detail/${route.params.id}`,
        })
        .then((response) => {
            return response.data;
        })
        .then(data => {
        })
        .finally(() => {
            busy.remove('fonts.edit:fetch-item');
        });


    notifier.async(
        promise,
        'Font details loaded.',
        err => {
            notifier.alert(
                err.response && err.response.status === 404
                    ? 'Font not found.'
                    : 'Failed to load font details.'
            );
            router.go(-1);
        },
        'Loading font details...'
    );
});

</script>