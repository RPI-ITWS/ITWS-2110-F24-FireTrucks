TO DO:
   History Section
   - The history of the constitution needs to be filled in
   - The history section should be collapsable. When any of the #history div is clicked, it should expand to reveal the history. When it is clicked again, it chould collapse. It should start collapsed and the .fa-chevron-up should be switched to .fa-chevron-down arrow. There should also be a smooth opening and closing animation for expanding and collapsing this section.
   - The 'The Document' section needs to be filled in. It should probably be in a JSON file because otherwise the HTML will become too hard to navigate.
   - The interpretations need to be filled in and linked to the appropriate article sections.
   - If there are any other fancy JS things you can think of adding, feel free to add it.

NOTES:
- Feel free to change the font. I thought it looked kinda ye olde so I thought it would fit but if it too hard to read or you dont like it, I put a couple of alternatives in the body tag in index.css
- The idea for the page as a whole is that it is not scrollable itself. I was able to format the size of #main div to make this work when #history div is collapsed, but I could not ALSO make it work for when it was expanded. I just settled for having the page not be scollable when it is collapsed. If anyone who reads this knows how to fix it and feels like doing so, feel free.
- I thought it might be better if intead of interpreting a whole article at once, we could just interpret one section at a time. If y'all disagree, feel free to change it.
- When I was researching how to change how the scroll bar looked, it said that it is dependent on the browser that you use. I think it works on chrome and safari but not on firefox (there is a different way to do it for firefox but I am not sure if it is needed). Edge and explorer apparently dont support any changes to the scroll bar.
- Dont forget to check that the code validates afterwards.

REFERENCES:
- Font: Quintessential by Google Fonts
- Feather Icon: FontAwesome 
- Constitution Document: constitution.congress.gov/constitution/