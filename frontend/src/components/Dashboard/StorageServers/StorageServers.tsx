"use client";

import React, { useEffect, useState } from "react";
import StorageServerView from "./StorageServerView";
import StorageServerForm from "./StorageServerForm";

interface StorageServersProps {}

function StorageServers({}: StorageServersProps) {
  const [render, setRender] = React.useState(false);
  const [option, setOption] = useState<string | null>(null);
  const [id, setId] = useState<string | null>(null);

  useEffect(() => {
    const queryParams = new URLSearchParams(window.location.search);
    const option = queryParams.get("option");
    const id = queryParams.get("id");
    setOption(option);
    setId(id);
  }, [render]);

  if (option === "create") {
    return <StorageServerForm render={render} setRender={setRender} />;
  }

  if (option === "edit") {
    return <StorageServerForm id={id!} render={render} setRender={setRender} />;
  }

  return <StorageServerView render={render} setRender={setRender} />;
}

export default StorageServers;
