<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Unlayer Editor</title>
    <style>
        body,
        html {
            height: 100%;
            padding: 0;
            margin: 0;
            overflow: hidden
        }

        #editor {
            width: 100%;
            height: 100%
        }
    </style>
</head>

<body>
    <div id="editor"></div>
    <script src="//editor.unlayer.com/embed.js"></script>
    <script>
        unlayer.init({
            id: 'editor',
            projectId: 6273,
            templateId: "17478",
            tools: {
                social: {
                    enabled: false
                }
            },
            appearance: {
                theme: 'light'
            },
            features: {
                userUploads: false
            }
        })
        unlayer.addEventListener('design:updated', function(updates) {
            // Design is updated by the user

            unlayer.exportHtml(function(data) {
                var json = data.design; // design json
                var html = data.html; // design html
                alert(json);
                // Save the json, or html here
            })
        })
        // unlayer.init({
        //     id: "editor",
        //     projectId: 167,
        //     displayMode: "email",
        //     features: {
        //         userUploads: false
        //     }
        // });
    </script>
</body>

</html>
<!-- <div id="editor"></div>
<script>
    unlayer.init({
        id: 'editor',
        projectId: 6273,
        templateId: "17478",
        tools: {
            social: {
                enabled: false
            }
        },
        appearance: {
            theme: 'light'
        },
        features: {
            userUploads: false
        }
    })
</script> -->