<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nedcore</title>
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <link rel="shortcut icon" href="{{ asset('asset/images/logo.png') }}" type="image/x-icon" />
    <link href="{{ asset('asset/css/index.css') }}" rel="stylesheet">
</head>

<body class="">
    <div class="container px-6 pb-8 pt-12 min-h-dvh text-b300 bg-y50 dark:bg-darkY50">
        <div class="flex justify-center items-center text-center flex-col">
            <h1 class="text-2xl font-medium dark:text-white">
                S'inscrire
            </h1>
            <p class="tet-sm text-n500 dark:text-darkN500 pt-3 px-4">
                Créez votre compte NedCore.
            </p>
        </div>

        <form class="flex flex-col gap-4 pt-8">
            <div class="flex flex-col justify-start">
                <p class="text-sm font-semibold pb-2 dark:text-white">
                    Noms
                </p>
                <div class="p-4 bg-n20 border border-n40 dark:bg-darkN20 dark:border-darkN40 rounded-xl">
                    <input type="text" placeholder="Enter first name"
                        class="w-full bg-transparent outline-none placeholder:text-n90 text-sm dark:text-white" />
                </div>
            </div>
            <div class="flex flex-col justify-start">
                <p class="text-sm font-semibold pb-2 dark:text-white">
                    Prénoms
                </p>
                <div class="p-4 bg-n20 border border-n40 dark:bg-darkN20 dark:border-darkN40 rounded-xl">
                    <input type="text" placeholder="Enter last name"
                        class="w-full bg-transparent outline-none placeholder:text-n90 text-sm dark:text-white" />
                </div>
            </div>
            <div class="flex flex-col justify-start">
                <p class="text-sm font-semibold pb-2 dark:text-white">
                    Email
                </p>
                <div class="p-4 bg-n20 border border-n40 dark:bg-darkN20 dark:border-darkN40 rounded-xl">
                    <input type="text" placeholder="Enter email"
                        class="w-full bg-transparent outline-none placeholder:text-n90 text-sm dark:text-white" />
                </div>
            </div>
            <div class="flex flex-col justify-start">
                <p class="text-sm font-semibold pb-2 dark:text-white">
                    Mot de passe
                </p>
                <div
                    class="p-4 bg-n20 border border-n40 dark:bg-darkN20 dark:border-darkN40 rounded-xl flex justify-between items-center">
                    <input type="password" placeholder="password"
                        class="flex-1 bg-transparent outline-none text-sm passwordField dark:text-white" />
                    <i
                        class="ph ph-eye-slash passowordShow cursor-pointer text-lg !leading-none dark:text-darkB300"></i>
                </div>
            </div>
            <div class="flex flex-col justify-start">
                <p class="text-sm font-semibold pb-2 dark:text-white">
                    Confirmer le mot de passe
                </p>
                <div
                    class="p-4 bg-n20 border border-n40 dark:bg-darkN20 dark:border-darkN40 rounded-xl flex justify-between items-center">
                    <input type="password" placeholder="Confirm password"
                        class="flex-1 bg-transparent outline-none text-sm confirmPasswordField dark:text-white" />
                    <i
                        class="ph ph-eye-slash confirmPasswordShow cursor-pointer dark:text-darkB300 text-lg !leading-none"></i>
                </div>
            </div>
            <div class="my-3 flex">
                <a href="#"
                    class="flex-1 py-3 bg-b300 dark:bg-darkB300 text-white text-center rounded-xl font-semibold">
                    S'inscrire
                </a>
            </div>
        </form>

       
        <div class="pt-4 text-center text-sm text-n500 dark:text-darkN500">
            Vous avez déjà un compte ?
            <a href="          {{ route('connexion') }}" class="text-b300 font-medium dark:text-darkB300">
                Se connecter
            </a>
            ici.
        </div>
    </div>

    <!-- ======Javascript Dependencies -->
    <script src="{{ asset('asset/js/main.js') }}"></script>
    <script defer src="{{ asset('asset/js/index.js') }}"></script>
</body>

<!-- Mirrored from pixner.net/html/readease/main-demo/sign-up.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 13 Oct 2025 09:26:16 GMT -->

</html>
