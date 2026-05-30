<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "includes/head.php" ?>
    <title>AllenHouse Lucknow| Peer Educators Club</title>
</head>

<body>

    <?php include "includes/header.php" ?>

    <div class="main relative sm:top-[20px] mb-[40px] sm:mb-[120px] mx-0 sm:mx-2">
        <div class="mt-8 mx-3 2xl:w-[1280px] lg:w-[1024px] md:w-[767px] sm:w-[640px] sm:mx-auto sm:px-5 px-3">
            <div class="mt-10 relative">
                <div class="absolute top-[-100px] -z-50">
                    <img src="https://res.cloudinary.com/dvzfuapyy/image/upload/v1730307222/Group_53_s3txur.png"
                        class="w-[100%] object-top" alt="">
                </div>
                <h1
                    class="text-[32px] sm:hidden block font-[700] text-blue-main uppercase text-center mb-5 hr-line relative leading-9">
                    The Peer <span class="sm:hidden"> <br></span>Educators Club
                </h1>
                <div class="sm:flex gap-9">
                    <div class="sm:w-[40%]">
                        <img src="assets/images/b4.jpg" alt="" class="w-[100%]" alt="">
                    </div>
                    <div class="sm:w-[60%]">
                        <h1
                            class="sm:text-[32px] sm:block hidden font-[700] text-blue-main uppercase  sm:mb-1 hr-line relative leading-9">
                            The Peer
                            <span class="sm:hidden"></span> Educators Club
                        </h1>
                        <p class="sm:text-[18px] text-[16px] text-gray-500 font-[400] mt-3">
                            At Allenhouse Public School Khalasi Lines is dedicated to fostering a supportive, inclusive,
                            and empowering environment where students can share knowledge, develop leadership skills,
                            and contribute positively to the school community. Our mission is to Educate, Enrich, and
                            Empower a network of well-informed individuals who can act as resources and role models for
                            their peers.
                        </p>
                        <div class="mt-5">
                            <div>
                                <h3 class="text-gray-600 text-[18px]">Goals-</h3>
                                <ul class="mt-3 ml-8">
                                    <li class="text-gray-500 text-[17px] mb-2">* Promote Peer Learning </li>
                                    <li class="text-gray-500 text-[17px] mb-2">* Enhance Leadership Skills</li>
                                    <li class="text-gray-500 text-[17px] mb-2">* Raise Awareness </li>
                                    <li class="text-gray-500 text-[17px] mb-2">* Build Community</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php include "includes/footer.php" ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.0.2/glide.js"></script>
    <script>
    var glide01 = new Glide('.glide-01', {
        type: 'carousel',
        focusAt: 'center',
        perView: 3.5,
        autoplay: 3500,
        animationDuration: 700,
        gap: 24,
        classes: {
            activeNav: '[&>*]:bg-slate-700',
        },
        breakpoints: {
            1024: {
                perView: 2
            },
            640: {
                perView: 1
            }
        },
    });
    glide01.mount();
    </script>
</body>

</html>