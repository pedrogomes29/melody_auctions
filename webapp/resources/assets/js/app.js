/**
 * First, we will load all of this project's Javascript utilities and other
 * dependencies. Then, we will be ready to develop a robust and powerful
 * application frontend using useful Laravel and JavaScript libraries.
 */

require("./bootstrap");

Echo.private(`users.${window.User.id}`).listen("AuctionCancelled", (e) => {
    console.log(e.auction);
});

Echo.private(`users.${window.User.id}`).listen("NewBid", (e) => {
    console.log(e.auction);
    console.log(e.bid);
});
