function generateCartItem(caller){
    var cartItem = document.createElement("div");


    var menuLine = caller.parentElement; //menu-item

    var number = menuLine.getElementsByClassName("menu-item-number")[0].textContent;
    var name = menuLine.getElementsByClassName("menu-item-name")[0].textContent;
    var price = menuLine.getElementsByClassName("menu-item-price")[0].textContent;

    var d1 = document.createElement("div");
    var d2 = document.createElement("div");
    var d3 = document.createElement("div");
    var d4 = document.createElement("div");

    let remBtn = document.createElement("i");
    remBtn.classList.add("gg-trash");

    d4.addEventListener("click", function(){
        removeFormCart(d4);
    });

    d1.textContent = number;
    d2.textContent = name;
    d3.textContent = price;
    d4.classList.add("cart-item-trash");

    cartItem.classList.add("cart-item");

    d1.classList.add("cart-item-number");
    d2.classList.add("cart-item-name");
    d3.classList.add("cart-item-price");

    cartItem.appendChild(d1);
    cartItem.appendChild(d2);
    cartItem.appendChild(d3);
    cartItem.appendChild(d4);

    d4.appendChild(remBtn);

    return cartItem;

}

function addToCart(caller){
    let cart = document.getElementById("cart-items");
    let virtualCart = document.getElementById("virt-cart");
    let item = generateCartItem(caller)
    let option = document.createElement("option");
    option.value = item.children[0].textContent.replace(/\t|\n/g, '');
    option.selected = true;
    virtualCart.appendChild(option);
    cart.appendChild(item);
    calculateSum();
}

function removeFormCart(caller){
    let cartItem = caller.parentElement;
    let cart = document.getElementById("cart-items");
    let virtualCart = document.getElementById("virt-cart");
    for(let i = 0; i < virtualCart.children.length; i++){
        if(virtualCart.children[i].value === cartItem.children[0].textContent.replace(/\t|\n/g, '')){
            virtualCart.removeChild(virtualCart.children[i]);
            break;
        }
    }
    cart.removeChild(cartItem);
    calculateSum();
}


function initButtons(){
    let buttons = document.getElementsByClassName("into-shoppingbag");

    for(let i = 0; i < buttons.length; i++){

        buttons[i].addEventListener("click", (event) =>{
            addToCart(buttons[i]);
        });


    }
    //document.getElementById("open-cart").addEventListener("click", toggleCart);
}

function getSum(){
    let cart = document.getElementById("cart-items");
    let items = cart.getElementsByClassName("cart-item-price");

    let sum = 0;
    for(let i = 0; i < items.length; i++){
        let strNum = items[i].textContent.replace(/\t/g, '');

        strNum = strNum.replace(',','.').replace('€','');
        let num = parseFloat(strNum);
        sum += num;
    }
    sum = sum.toFixed(2);
    return sum;
}

function calculateSum(){
    let sum = getSum();
    document.getElementById("cart-sum").textContent = "Summe: " + sum.toString().replace('.',',') + "€";
}

function toggleCart(){
    let itemContainer = document.getElementById("cart-items");

    itemContainer.classList.toggle("visible-cart");

    let  arrowContainer = document.getElementById("open-cart").getElementsByClassName("arrow");
    arrowContainer[0].classList.toggle("up");
    arrowContainer[0].classList.toggle("down");
}

function submitOrder(){
    let cart = document.getElementById("virt-cart");
    let form = document.getElementById("order-submission");
    let sum = getSum();
        form.submit();

}


window.onload = initButtons;