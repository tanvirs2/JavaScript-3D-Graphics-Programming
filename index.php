<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="../washingMechine/threeJS/three.min.js"></script>
    <script src="../washingMechine/threeJS/OrbitControls.js"></script>
    <span style="color: red"></span>
    <style>
        body {
            overflow: hidden;
        }
    </style>
</head>
<body>

<script>
    var scene = new THREE.Scene();
    var camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 9000);
    var renderer = new THREE.WebGLRenderer({antialias: true});
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setClearColor('black');
    renderer.setPixelRatio(devicePixelRatio);
    document.body.appendChild(renderer.domElement);
    var control = new THREE.OrbitControls(camera, renderer.domElement);
    camera.position.y = 80;

    /* Texture */
    var r = "star/";

    var urls = [
        r + "px.jpg", r + "nx.jpg",
        r + "py.jpg", r + "ny.jpg",
        r + "pz.jpg", r + "nz.jpg"
    ];

    var textureCube = new THREE.CubeTextureLoader().load(urls);
    scene.background = textureCube;

    var textureLoader = new THREE.TextureLoader();
    /* Texture */


    // function section //
    function spGeom(radius, widthSegments, heightSegments, orbitSize) {

        var shape = new THREE.Shape();
        shape.moveTo(orbitSize, 0);
        shape.absarc(0, 0, orbitSize, 0, 2 * Math.PI, false);
        var spacedPoints = shape.createSpacedPointsGeometry(128);
        spacedPoints.rotateX(THREE.Math.degToRad(-90));
        var orbit = new THREE.Line(spacedPoints, new THREE.LineBasicMaterial({
            color: 0x233151
        }));
        scene.add(orbit);

        return new THREE.SphereGeometry(radius, widthSegments, heightSegments);
    }
    function crMaterial(map, norMap) {
        return new THREE.MeshPhongMaterial({
            map: textureLoader.load(map),
            normalMap: textureLoader.load(norMap),
        });
    }
    function bsMaterial(map, norMap) {
        return new THREE.MeshBasicMaterial({
            map: textureLoader.load(map),
            normalMap: textureLoader.load(norMap),
        });
    }
    function crMesh(geo, mater) {
        return new THREE.Mesh(geo, mater);
    }
    // end function section //

    /* Light */
    var ambientLight = new THREE.AmbientLight(0xFFFFFF, 0.1);
    scene.add(ambientLight);
    var pointLight = new THREE.PointLight(0xFBFDAC, 2, 150);
    scene.add(pointLight);
    /* Light */


    // work section //
    var spriteMap = textureLoader.load( "star/snFl.png" );
    var spriteMaterial = new THREE.SpriteMaterial( { map: spriteMap, color: 0xffffff } );
    var sprite = new THREE.Sprite( spriteMaterial );
    scene.add( sprite );
    sprite.scale.set(65, 65);

    var sun = crMesh(spGeom(12,32,16, 35), bsMaterial('tex/SunTexture2.jpg', 'tex/SunTexture2_NRM.jpg'));
    var mercury = crMesh(spGeom(2,32,16, 20), crMaterial('tex/cd.jpg', 'tex/cd_NRM.jpg'));
    var pluto = crMesh(spGeom(4, 32, 16, 50), crMaterial('tex/ab.jpg', 'tex/ab_NRM.jpg'));
    var earth = crMesh(spGeom(5, 32, 16, 70), crMaterial('tex/bc.jpg', 'tex/bc_NRM.jpg'));
    var neptune = crMesh(spGeom(6,32,16, 90), crMaterial('tex/ef.jpg', 'tex/ef_NRM.jpg'));
    var mars = crMesh(spGeom(8,32,16, 0), crMaterial('tex/gh.jpg', 'tex/gh_NRM.jpg'));

    scene.add(sun); // #1
    scene.add(mercury); // #2
    scene.add(pluto); // #3
    scene.add(earth); // #4
    scene.add(neptune); // #5
    scene.add(mars); // #6

    // App Logic //
    var t = 0;
    var p = 0;
    var q = 0;
    var r = 0;
    var u = 0;

    var scRt = 0;

    function rotatingFunc(THRobj, planetSpd, roundWidth, rotateX, rotateY) {
        THRobj.rotation.x += rotateX;
        THRobj.rotation.y += rotateY;
        THRobj.position.x = roundWidth*Math.cos(planetSpd) + 0;
        THRobj.position.z = roundWidth*Math.sin(planetSpd) + 0;
    };

    function update() {
        t += 0.01;
        r += 0.007;
        p += 0.006;
        q += 0.003;
        u += 0.002;

        scRt -= 0.0009;

        sun.rotation.y += 0.003;

        //rotatingFunc(THRobj, planetSpd, roundWidth, rotateX, rotateY)
        rotatingFunc(mercury, t, 20, 0.001, 0.05);
        rotatingFunc(pluto, r, 35, 0.005, 0.01);
        rotatingFunc(earth, p, 50, 0, 0.02);
        rotatingFunc(neptune, q, 70, 0.01, 0.01);
        rotatingFunc(mars, u, 90, 0, 0.01);

        camera.position.x = 100 * Math.sin(scRt);
        camera.position.z = 100 * Math.cos(scRt);
        camera.lookAt(pluto.position);
    }

    // Draw Scene //
    function render() {
        renderer.render(scene, camera);
    }

    // App Loop //
    function appLoop() {
        requestAnimationFrame(appLoop);

        update();
        render();
    }

    var onResize = function () {
        var width = window.innerWidth, height = window.innerHeight;

        camera.aspect = width / height;
        camera.updateProjectionMatrix();
        renderer.setSize(width, height);
    };

    appLoop();
    window.addEventListener('resize', onResize, false);
</script>
</body>
</html>