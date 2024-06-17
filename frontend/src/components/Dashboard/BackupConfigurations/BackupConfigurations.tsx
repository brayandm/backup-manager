"use client";

import React from "react";
import Table from "@/components/Table";
import useSWR from "swr";
import { get } from "@/lib/backendApi";

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

const fetcher = (url: string) => get(url);

function BackupConfigurations({}: BackupConfigurationsProps) {
  const [order, setOrder] = React.useState<"asc" | "desc">("asc");
  const [orderBy, setOrderBy] = React.useState<keyof Data>("id");
  const [selected, setSelected] = React.useState<readonly number[]>([]);
  const [page, setPage] = React.useState(0);
  const [rowsPerPage, setRowsPerPage] = React.useState(5);

  const { data, error } = useSWR(
    `/backup-configurations?page=${
      page + 1
    }&pagination=${rowsPerPage}&sort_by=${orderBy}&sort_order=${order}`,
    fetcher
  );

  if (data) console.log(data);

  const headCells: readonly HeadCell[] = [
    {
      id: "name",
      numeric: false,
      label: "Name",
    },
  ];

  return (
    data && (
      <Table
        headCells={headCells}
        rows={data.data.data as Data[]}
        total={data.data.total}
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
    )
  );
}

export default BackupConfigurations;
