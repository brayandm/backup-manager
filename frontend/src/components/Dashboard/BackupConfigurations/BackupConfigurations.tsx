"use client";

import React from "react";
import Table from "@/components/Table";

interface BackupConfigurationsProps {}

interface Data {
  id: number;
  [key: string]: any;
}
interface HeadCell {
  id: keyof Data;
  label: string;
  numeric: boolean;
}

function BackupConfigurations({}: BackupConfigurationsProps) {
  const [order, setOrder] = React.useState<"asc" | "desc">("asc");
  const [orderBy, setOrderBy] = React.useState<keyof Data>("id");
  const [selected, setSelected] = React.useState<readonly number[]>([]);
  const [page, setPage] = React.useState(0);
  const [rowsPerPage, setRowsPerPage] = React.useState(5);

  const headCells: readonly HeadCell[] = [
    {
      id: "name",
      numeric: false,
      label: "Name",
    },
  ];

  const rows = [
    { id: 1, name: "Cupcake" },
    { id: 2, name: "Donut" },
    { id: 3, name: "Eclair" },
    { id: 4, name: "Frozen yoghurt" },
  ];

  return (
    <Table
      headCells={headCells}
      rows={rows}
      order={order}
      setOrder={setOrder}
      orderBy={orderBy}
      setOrderBy={setOrderBy}
      selected={selected}
      setSelected={setSelected}
      page={page}
      setPage={setPage}
      rowsPerPage={rowsPerPage}
      setRowsPerPage={setRowsPerPage}
    />
  );
}

export default BackupConfigurations;
