import "./index.scss"
import {TextControl, Flex, FlexBlock, FlexItem, Button, Icon, PanelBody, PanelRow, ColorPicker} from "@wordpress/components";
import {InspectorControls, BlockControls, AlignmentToolbar} from "@wordpress/block-editor"
import {ChromePicker} from "react-color"

(function() {
    let locked = false
    
    wp.data.subscribe(() => {
        const results = wp.data.select("core/block-editor").getBlocks().filter((block) => {
            return block.name == "ourplugin/are-you-paying-attention" && block.attributes.correctAnswer == undefined
        })

        if (results.lenght && locked == false) {
            locked = true
            wp.data.dispatch("core/editor").lockPostSaving("noanswer")
        }

        if (!results.lenght && locked) {
            locked = false
            wp.data.dispatch("core/editor").unlockPostSaving("noanswer")
        }
    })
})()



wp.blocks.registerBlockType("ourplugin/are-you-paying-attention", {
    title: "Are You Paying Attention?",
    icon: "smiley",
    category: "common",
    attributes: {
        question: {type: "string"},
        answers: {type: "array", default: ["red"]},
        correctAnswer: {type: "number", default: undefined},
        bgColor: {type: "string", default: "#EBEBEB"},
        theAlignment: {type: "string", default: "left"}
    },
    description: "Give your audience a chance to prove their comprehension",
    example: {
        attributes: {
            question: "What is 2 + 2",
            answers: ["4", "5", "10"],
            correctAnswer: 0,
            bgColor: "#EBEBEB",
        }
    },
    edit: EditComponent,
    save: function (props) {
        return null
    }
})


function EditComponent(props) { 

    function updateQuestion(value) {
        props.setAttributes({question: value})
    }

    function deleteAnswer(indexToDelete) {
        const newAnwsers = props.attributes.answers.filter(function(x, index) {
            return index != indexToDelete
        })
        props.setAttributes({answers: newAnwsers})

        if (indexToDelete == props.attributes.correctAnswer) {
            props.setAttributes({correctAnswer: undefined})
        }
    }

    function markAsCorrect(index) {
        props.setAttributes({correctAnswer: index})
    }

    return (
        <div className="paying-attention-edit-block" style={{backgroundColor: props.attributes.bgColor}}>
            <BlockControls>
                <AlignmentToolbar value={props.attributes.theAlignment} onChange={x => props.setAttributes({theAlignment: x})} />
            </BlockControls>
            <InspectorControls>
                <PanelBody title="Background Color" initialOpen={true}>
                    <PanelRow>
                        <ChromePicker color={props.attributes.bgColor} onChangeComplete={x => props.setAttributes({bgColor: x.hex})} disableAlpha={true} />
                    </PanelRow>
                </PanelBody>
            </InspectorControls>
            <TextControl label="Question:" value={props.attributes.question} onChange={updateQuestion} style={{fontSize: "20px"}} /> 
            <p style={{fontSize: "13px", margin: "20px 0 8px 0"}}>Anwsers:</p>
            {props.attributes.answers.map( (answer, index) => { 
                return (
                <Flex>
                    <FlexBlock>
                        <TextControl autoFocus={answer == undefined} value={answer} onChange={(newValue) => { /* This function is inlined because the TextControl component sends data to newValue and we cant use that in an outside function */
                            const newAnwsers = props.attributes.answers.concat([]) /* We cant directly change the state of an array in react, we have to send a new copy of it instead that we can change */
                            newAnwsers[index] = newValue
                            props.setAttributes({answers: newAnwsers})
                       }}/>
                    </FlexBlock>
                    <FlexItem>
                        <Button onClick={() => markAsCorrect(index)}>
                            <Icon className="mark-as-correct" icon={props.attributes.correctAnswer == index ? "star-filled" : "star-empty"} />
                        </Button>
                    </FlexItem>
                    <FlexItem>
                        <Button isLink className="attention-delete" onClick={() => deleteAnswer(index)}>Delete</Button>
                    </FlexItem>
                </Flex>
                )
            } )}
            <Button isPrimary onClick={() => {
                props.setAttributes({answers: props.attributes.answers.concat([undefined])})
            }}>Add another answer</Button>
        </div>
    )
}