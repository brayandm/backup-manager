"use client";

import React from "react";
import Table from "@/components/Table";
import useSWR from "swr";
import { get } from "@/lib/backendApi";
import { Fab, Tooltip } from "@mui/material";
import AddIcon from "@mui/icons-material/Add";

interface BackupConfigurationsProps {}

interface Data {
  id: number;
  [key: string]: any;
}
interface HeadCell {
  id: keyof Data;
  isOrderable: boolean;
  label: string;
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

  const columns: readonly HeadCell[] = [
    {
      id: "id",
      isOrderable: true,
      label: "ID",
    },
    {
      id: "name",
      isOrderable: true,
      label: "Name",
    },
    {
      id: "created_at",
      isOrderable: true,
      label: "Created At",
    },
    {
      id: "total_backups",
      isOrderable: true,
      label: "Total Backups",
    },
    {
      id: "total_size",
      isOrderable: true,
      label: "Total Size",
    },
    {
      id: "last_backup",
      isOrderable: true,
      label: "Last Backup",
    },
    {
      id: "status",
      isOrderable: true,
      label: "Status",
    },
    {
      id: "update",
      isOrderable: false,
      label: "Update",
    },
  ];

  return data ? (
    <div
      style={{
        width: "86vw",
        display: "flex",
        flexDirection: "column",
        alignItems: "end",
      }}
    >
      <Table
        title="Backup Configurations"
        columns={columns}
        rows={data.data.data as Data[]}
        count={data.data.total}
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
      <div
        style={{
          margin: "10px",
        }}
      >
        <Tooltip title="Add Backup Configuration" placement="left" arrow>
          <Fab color="primary" aria-label="add">
            <AddIcon />
          </Fab>
        </Tooltip>
      </div>
    </div>
  ) : (
    <> </>
  );
}

export default BackupConfigurations;
