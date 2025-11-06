<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nedcore</title>
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <link rel="shortcut icon" href="{{ asset('assets/img/authentication/logo_nedcore.JPG') }}" type="image/x-icon" />
    <link href="{{ asset('asset/css/index.css') }}" rel="stylesheet">
</head>

<body class="">
    <div class="container px-6 pb-8 pt-12 min-h-dvh text-b300 bg-y50 dark:bg-darkY50">
        <div class="flex justify-center items-center text-center flex-col">
            <img src="{{ asset('assets/img/authentication/logo_nedcore.png') }}" alt="logo" width="250"
                class="size-20 object-contain" />
            <h1 class="text-2xl font-medium dark:text-white" style="margin-top: 20px">Se connecter</h1>
            <p class="tet-sm text-n500 dark:text-darkN500 pt-3 px-2">
                Accédez à NedCore en utilisant votre code entreprise,votre identifiant et votre mot de passe.
            </p>
        </div>

        <form class="flex flex-col gap-4 pt-8">
            <div class="flex flex-col justify-start">
                <p class="text-sm font-semibold pb-2 dark:text-white">Identifiant</p>
                <div class="p-4 bg-n20 border border-n40 dark:bg-darkN20 dark:border-darkN40 rounded-xl">
                    <input type="text" placeholder="Enter email"
                        class="w-full bg-transparent outline-none placeholder:text-n90 text-sm dark:text-white" />
                </div>
            </div>
            <div class="flex flex-col justify-start">
                <p class="text-sm font-semibold pb-2 dark:text-white">Mot de passe</p>
                <div
                    class="p-4 bg-n20 border border-n40 dark:bg-darkN20 dark:border-darkN40 rounded-xl flex justify-between items-center">
                    <input type="password" placeholder="password"
                        class="flex-1 bg-transparent outline-none text-sm passwordField dark:text-white" />
                    <i
                        class="ph ph-eye-slash passowordShow cursor-pointer text-n90 text-lg !leading-none dark:text-darkB300"></i>
                </div>
            </div>

            <a href="forgot-password.html" class="font-medium text-end dark:text-darkB300">
                Mot de passe oublié ?</a>

            <div class="my-3 flex">
                <a href="{{ route('actualite') }}"
                    class="flex-1 py-3 bg-b300 text-white text-center rounded-xl font-semibold dark:bg-darkB300">Se
                    connecter</a>
            </div>
        </form>

        
        <div class="pt-4 text-center text-sm text-n500 dark:text-darkN500">
            Vous n'avez pas de compte ?  
            <a href="
            {{ route('inscription') }}
            " class="text-b300 font-medium dark:text-darkB300">Inscrivez-vous</a>
            ici.
        </div>
    </div>

    <!-- ======Javascript Dependencies -->
    <script src="{{ asset('asset/js/main.js') }}"></script>
    <script defer src="{{ asset('asset/js/index.js') }}"></script>
</body>

<!-- Mirrored from pixner.net/html/readease/main-demo/sign-in.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 13 Oct 2025 09:26:13 GMT -->

</html>
