-- Create a new empty gameObject with optionnal parametters
function GameObject.New(name, params, g)
    if name == GameObject then
        name = params
        params = g
    end

    -- errors
    local errorHead = "GameObject.New(name[, params]) : "

    if type(name) ~= "string" then
        error(errorHead .. "Argument 'name' is not a string. Must be the gameObject name.")
    end

    if params == nil then params = {} end

    if type(params) ~= "table" then
        error(errorHead .. "Argument 'params' is not a table. Must be a table.")
    end
    
    --
    local go = CraftStudio.CreateGameObject(name)

    go = ApplyParamsToGameObject(go, params, errorHead)    

    return go
end

-- Add a scene as a gameObject with optionnal parametters
function GameObject.Instantiate(goName, sceneName, params, g)
    if goName == GameObject then
        goName = sceneName
        sceneName = params
        params = g
    end

    -- errors
    local errorHead = "GameObject.Instantiate(gameObjectName, sceneName[, params]) : "

    if type(goName) ~= "string" then
        error(errorHead .. "Argument 'gameObjectName' is not a string. Must be the gameObject name.")
    end

    if type(sceneName) ~= "string" then
        error(errorHead .. "Argument 'sceneName' is not a string. Must be the scene name.")
    end

    if params == nil then params = {} end

    if type(params) ~= "table" then
        error(errorHead .. "Argument 'params' is not a table. Must be a table.")
    end
    
    --
    local go = CraftStudio.Instantiate(goName, sceneName)

    go = ApplyParamsToGameObject(go, params, errorHead) 

    return go
end

-- apply the content of params to the gameObject in argument
local function ApplyParamsToGameObject(go, params, errorHead)
    if params.parent ~= nil then
        local parent = params.parent

        if type(parent) == "string" then
            parent = CraftStudio.FindGameObject(parent)

            if parent == nil then
                error(errorHead .. "parent name in parameters [" .. params.parent .. "] does not match any gameObject.")
            end
        end

        if type(params.parentKeepLocalTransform) == "boolean" then
            go:SetParent(parent, params.keepLocalTransform)
        else
            go:SetParent(parent)
        end
    end

    --  position
    if params.position ~= nil then
        go.transform:SetPosition(params.position)
    end

    if params.localPosition ~= nil then
        go.transform:SetLocalPosition(params.localPosition)
    end

    -- orientation
    if params.orientation ~= nil then
        go.transform:SetOrientation(params.orientation)
    end

    if params.localOrientation ~= nil then
        go.transform:SetLocalOrientation(params.localOrientation)
    end

    -- Euler Angles
    if params.eulerAngles ~= nil then
        go.transform:SetEulerAngles(params.eulerAngles)
    end

    if params.localEulerAngles ~= nil then
        go.transform:SetLocalEulerAngles(params.localEulerAngles)
    end

    -- scale
    if params.scale ~= nil then
        go.transform:SetLocalScale(params.scale)
    end

    -- components
    if params.model ~= nil then
        local model = params.model

        if type(model) == "string" then
            model = CraftStudio.FindAsset(model, "Model")

            if model == nil then
                error(errorHead .. "model name in parameters [" .. params.model .. "] does not match any model.")
            end
        end

        go:CreateComponent("ModelRenderer"):SetModel(model)
    end

    if params.map ~= nil then
        local map = params.map

        if type(map) == "string" then
            map = CraftStudio.FindAsset(map, "Map")

            if map == nil then
                error(errorHead .. "map name in parameters [" .. params.map .. "] does not match any map.")
            end
        end

        go:CreateComponent("MapRenderer"):SetMap(map)
    end

    if params.camera ~= nil then
        go:CreateComponent("Camera")
    end

    -- scripts
    if params.scripts == nil then
        params.scripts = {}
    end

    if params.script ~= nil then
        table.insert(params.scripts, params.script)
    end

    for i, script in ipairs(params.scripts) do
        if type(script) ==  "string" then
            script = CraftStudio.FindAsset(script, "ScriptedBehavior")

            if script == nil then
                error(errorHead .. "script name in parameters [" .. script .. "] does not match any script.")
            end
        end

        go:CreateScripteBehavior(script)
    end 

    return go
end


-- Get the gameObject of the specified name
function GameObject.Get(name, g)
    if name == GameObject then
        name = g
    end

    return CraftStudio.FindGameObject(name)
end

function GameObject.Find(name, g)
    if name == GameObject then
        name = g
    end

    return GameObject.Get(name)
end


--------------------------------------------------
-- Components
--------------------------------------------------


-- tell wether the text is a script
local function IsScript(text)
    scripts = {"script", "Script", "ScriptedBehavior", "scriptedBehavior", "Scriptedbehavior", "scriptedbehavior"}

    for i, value in ipairs(scripts) do
        if value == text then
            return true
        end
    end

    return false
end


local assetTypeFromComponentType = {
    Script = "Script",
    ModelRenderer = "Model",
    MapRenderer = "Map",
}


-- Add a component to the gameObject and optionnaly set the model, map or script asset
function GameObject:AddComponent(componentType, asset, g)
    local errorHead = "GameObject:AddComponent(componentType[, asset or asset name]) : "

    if getmetatable(self) ~= GameObject then -- pas appelé depuis un gameObject
        error(errorHead .. "Not called from a gameObject. Your must use a colon ( : ) between the gameObject and the method name. Ie : self.gameObject:AddComponent()")
    end

    if componentType == nil or type(componentType) ~= "string" then
        error(errorHead .. "Argument 'componentType' is nil or not a string. Must be the component type.")
    end

    -- get the asset if name is given
    -- it's done here because we need the asset right away if it's a script
    if asset ~= nil && if type(asset) == "string" then
        local assetType = assetTypeFromComponentType[componentType]
        local assetName = asset
        asset = CraftStudio.FindAsset(assetName, assetType)

        if asset == nil then
            error(errorHead .. "Asset not found. Component type='" .. componentType .. "', asset type='" .. assetType .. "', asset name'" .. assetName .. "'")
        end
    end

    -- 
    local component = nil

    if IsScript(componentType) then
        component = self:CreateScriptedBehavior(asset)
    else
        component = self:CreateComponent(componentType)

        if asset ~= nil then
            if componentType == "ModelRenderer" then
                component:SetModel(asset)
            elseif componentType == "MapRenderer" then
                component:SetMap(asset)
            end
        end
    end

    return component
end

-- Script
function GameObject:AddScript(assetNameOrAsset)
    local errorHead = "GameObject:AddScript(assetNameOrAsset) : "

    if getmetatable(self) ~= GameObject then
        error(errorHead .. "Not called from a gameObject. Your must use a colon ( : ) between the gameObject and the method name. Ie : self.gameObject:AddScript()")
    end

    if type(assetNameOrAsset) == nil then
        error(errorHead .. "Argument 'assetNameOrAsset' is nil. Must be the script name or the script asset.")
    end

    return self:AddComponent("Script", assetNameOrAsset)
end

function GameObject:AddScriptedBehavior(assetNameOrAsset)
    local errorHead = "GameObject:AddScriptedBehavior(assetNameOrAsset) : "

    if getmetatable(self) ~= GameObject then
        error(errorHead .. "Not called from a gameObject. Your must use a colon ( : ) between the gameObject and the method name. Ie : self.gameObject:AddScriptedBehavior()")
    end

    if type(assetNameOrAsset) == nil then
        error(errorHead .. "Argument 'assetNameOrAsset' is nil. Must be the script name or the script asset.")
    end

    return self:AddComponent("Script", assetNameOrAsset)
end

-- Model
function GameObject:AddModel(assetNameOrAsset)
    local errorHead = "GameObject:AddModel(assetNameOrAsset) : "

    if getmetatable(self) ~= GameObject then
        error(errorHead .. "Not called from a gameObject. Your must use a colon ( : ) between the gameObject and the method name. Ie : self.gameObject:AddModel()")
    end

    if type(assetNameOrAsset) == nil then
        error(errorHead .. "Argument 'assetNameOrAsset' is nil. Must be the model name or the model asset.")
    end

    return self:AddComponent("ModelRenderer", assetNameOrAsset)
end

function GameObject:AddModelRenderer(assetNameOrAsset)
    local errorHead = "GameObject:AddModelRenderer(assetNameOrAsset) : "

    if getmetatable(self) ~= GameObject then
        error(errorHead .. "Not called from a gameObject. Your must use a colon ( : ) between the gameObject and the method name. Ie : self.gameObject:AddModelRenderer()")
    end

    if type(assetNameOrAsset) == nil then
        error(errorHead .. "Argument 'assetNameOrAsset' is nil. Must be the model name or the model asset.")
    end

    return self:AddComponent("ModelRenderer", assetNameOrAsset)
end

-- Map
function GameObject:AddMap(assetNameOrAsset)
    local errorHead = "GameObject:AddMap(assetNameOrAsset) : "

    if getmetatable(self) ~= GameObject then
        error(errorHead .. "Not called from a gameObject. Your must use a colon ( : ) between the gameObject and the method name. Ie : self.gameObject:AddMap()")
    end

    if type(assetNameOrAsset) == nil then
        error(errorHead .. "Argument 'assetNameOrAsset' is nil. Must be the map name or the map asset.")
    end

    return self:AddComponent("MapRenderer", assetNameOrAsset)
end

function GameObject:AddMapRenderer(assetNameOrAsset)
    local errorHead = "GameObject:AddMapRenderer() : "

    if getmetatable(self) ~= GameObject then
        error(errorHead .. "Not called from a gameObject. Your must use a colon ( : ) between the gameObject and the method name. Ie : self.gameObject:AddMapRenderer()")
    end

    if type(assetNameOrAsset) == nil then
        error(errorHead .. "Argument 'assetNameOrAsset' is nil. Must be the map name or the map asset.")
    end

    return self:AddComponent("MapRenderer", assetNameOrAsset)
end

-- Camera
function GameObject:AddCamera()
    local errorHead = "GameObject:AddCamera() : "

    if getmetatable(self) ~= GameObject then
        error(errorHead .. "Not called from a gameObject. Your must use a colon ( : ) between the gameObject and the method name. Ie : self.gameObject:AddCamera()")
    end

    return self:AddComponent("MapRenderer")
end


-- Get the first component of the specified type
-- function GameObject:GetComponent(componentType, g)
--     local errorHead = "GameObject:GetComponent(componentType) : "

--     if getmetatable(self) ~= GameObject then -- pas appelé depuis un gameObject
--         error(errorHead .. "Not called from a gameObject. Your must use a colon ( : ) between the gameObject and the method name. Ie : self.gameObject:GetComponent()")
--     end

--     if componentType == nil or type(componentType) ~= "string" then
--         error(errorHead .. "Argument 'componentType' is nil or not a string. Must be the component type.")
--     end

--     return self:GetComponent(componentType)
-- end

-- Script
function GameObject:GetScript(script)
    local errorHead = "GameObject:GetScript(scriptNameOrScriptAsset) : "

    if getmetatable(self) ~= GameObject then -- pas appelé depuis un gameObject
        error(errorHead .. "Not called from a gameObject. Your must use a colon ( : ) between the gameObject and the method name. Ie : self.gameObject:GetScript()")
    end

    if script == nil then
        error(errorHead .. "Argument 'scriptNameOrScriptAsset' is nil. Must be the script name or the script asset")
    end

    if script ~= nil and if type(script) == "string" then
        local scriptName = script
        script = CraftStudio.FindAsset(scriptName, "Script")
        
        if script == nil then
            error(errorHead .. "Script asset not found. Script name='" .. scriptName .. "'")
        end
    end

    return self:GetScriptedBehavior(script)
end

-- Model
function GameObject:GetModelRenderer()
    local errorHead = "GameObject:GetModelRenderer() : "

    if getmetatable(self) ~= GameObject then -- pas appelé depuis un gameObject
        error(errorHead .. "Not called from a gameObject. Your must use a colon ( : ) between the gameObject and the method name. Ie : self.gameObject:GetModelRenderer()")
    end

    return self:GetComponent("ModelRenderer")
end

-- Map
function GameObject:GetMapRenderer()
    local errorHead = "GameObject:GetMapRenderer() : "

    if getmetatable(self) ~= GameObject then -- pas appelé depuis un gameObject
        error(errorHead .. "Not called from a gameObject. Your must use a colon ( : ) between the gameObject and the method name. Ie : self.gameObject:GetMapRenderer()")
    end

    return self:GetComponent("MapRenderer")
end

-- Camera
function GameObject:GetCamera()
    local errorHead = "GameObject:GetCamera() : "

    if getmetatable(self) ~= GameObject then -- pas appelé depuis un gameObject
        error(errorHead .. "Not called from a gameObject. Your must use a colon ( : ) between the gameObject and the method name. Ie : self.gameObject:GetCamera()")
    end

    return self:GetComponent("Camera")
end

-- Transform
function GameObject:GetTransform()
    local errorHead = "GameObject:GetTransform() : "

    if getmetatable(self) ~= GameObject then -- pas appelé depuis un gameObject
        error(errorHead .. "Not called from a gameObject. Your must use a colon ( : ) between the gameObject and the method name. Ie : self.gameObject:GetTransform()")
    end

    return self:GetComponent("Transform")
end


-- Destory Components




-- Destroy
function GameObject:Destroy()
    local errorHead = "GameObject:Destroy() : "

    if getmetatable(self) ~= GameObject then -- pas appelé depuis un gameObject
        error(errorHead .. "Not called from a gameObject. Your must use a colon ( : ) between the gameObject and the method name. Ie : self.gameObject:Destroy()")
    end

    CraftSudio.Destroy(self)
end






function Asset.Get(assetName, assetType)
    return CraftSudio.FindAsset(assetName, assetType)
end