Nova.booting((Vue, router) => {
    window.requestPrefix = '/nova-vendor/nova-dynamic-lang';

    router.addRoutes([
        {
            name: 'languages',
            path: '/languages',
            component: require('./components/Tool'),
        },
        {
            name: 'languages.create',
            path: '/languages/create',
            component: require('./components/CreateLanguage'),
        },
        {
            name: 'languages.edit',
            path: '/languages/edit/:code',
            component: require('./components/UpdateLanguage'),
            props: route => {
                return {
                    languageCode: route.params.code
                }
            }
        },
    ])
})
