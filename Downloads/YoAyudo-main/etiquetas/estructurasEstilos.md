- div: Selecciona todos los elementos `<div>`

- #cool: Selecciona cualquier elemento con "id="cool".(ejemplo)`<p>`

- strong: Selecciona los elementos todos los elementos que estan dentro de `<strong>`

- #fancy span: Selecciona cualquier elemento `<span>` que estan dentro de el elemento con id="fancy".
#id  A: Puedes combinar cualquier selector con el selector descendente.

- A.className: Puedes combinar el selector de clase con otros selectores, como el selector de tipo. Con una coma podes combinar 2 selectores. Con una * seleccionas todo.

- A*: Selecciona todos los elementos que esta dentro de A.

- A+B: Esto selecciona todos los elementos B que siguen directamente a A. Los elementos que siguen a otros se llaman hermanos. Están en el mismo nivel o profundidad.

- A ~ B: Puedes seleccionar todos los elementos hermanos que siguen a un elemento. Esto es similar al selector de adyacencia (A + B), pero selecciona todos los elementos siguientes en lugar de uno solo.

- A > B: Puedes seleccionar elementos que sean hijos directos de otros elementos. Un elemento hijo es cualquier elemento anidado directamente dentro de otro.
Los elementos que se encuentran a un nivel de anidamiento aún mayor se denominan elementos descendientes.

Flexbox
- justify-content: Sirve para mover el contenido en la posicion que se desea. Se utiliza con flex-start (alinea al lado izquierdo), flex-end (alinea al lado derecho), center (alinear al centro), space-between (muestra elementos con la misma distancia entre ellos) y space-around(muestra elementos con la misma separacion alrededor de ellos).

- align-item: Permite alinear el contenido en la posicion que se desea. Se utiliza las mismas tres propiedades del justify-content (flex-start, flex-end y center) y otros dos mas que son baseline (muestra elementos en la linea del contenedor) y stretch (los elements se estiran para ajustarse al contenedor).

- flex-direction: Sirve para mover el contenido en la direccion que se desea. Se utiliza con row (los elementos son colocados en la misma direccion del texto), row-reverse (los elementos son colocoados en la direccion opuesta del texto), column (los elementos de arriba a abajo) y column-reverse (los elementos se colocan de abajo a arriba).

- order: Permite ordenar en sentido positivo o negativo usando numeros enteros (...-2,-1,0,1,2...).

- align-self: Es lo mismo que align-item pero mueve un solo elemento.

- flow-wrap: Sirve para distribuir los elementos en la manera que se desea. Se utiliza con nowrap (cada elemento se ajusta en una sola linea), wrap (los elementos se envuelven alrededor de lineas adicionales) y wrap-reverse (los elementos se envuelven alrededor de lineas adicionales al reves).

- flex-flow: Es la combinacion entre flex-direction y flex-wrap. Esta propiedad acepta un valor de cada una separada con un espacio.

- align-content: Permite alnear las lineas en la direccion que se desea. Se utiliza todos los comandos de justify-content y stretch (la misma de align-item).

Box model
- content: Es el contenido de la "caja", donde aparecen textos e imagenes.

- padding: Limpia un area alrededor del content. El relleno es transparente.

- border: Un borde que rodea el padding y el content.

- margin: Limpia un area fuera del border. El margen es transparente

Unidades absolutas y relativas
- Unidades absolutas: Son unidades cuyo valor es fijo. Por ejemplo: px, cm, mm, in, pt, pc.
- Unidades relativas: Son unidades cuyo valor se pueden cambiar. Por ejemplo: rm, em, %, vw, vh, ch.
- Selectores usados:
body, header, nav, section, footer
.menu, .menu li a, .menu li a:hover
.imagenes img
button, button:hover
ul
form h1, form h2, form h3
.form-txt a:hover
.input-container
input, textarea, select, option, option:hover
section:hover
table